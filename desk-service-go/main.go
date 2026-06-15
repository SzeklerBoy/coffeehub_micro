package main

import (
	"log"
	"net/http"
	"os"
	"time"

	"github.com/gin-gonic/gin"
	"gorm.io/driver/mysql"
	"gorm.io/gorm"
)

// ==========================================
// 1. GORM ADATBÁZIS MODELLEK
// ==========================================

type Room struct {
	ID        uint      `gorm:"primaryKey" json:"id"`
	Name      string    `json:"name"`
	CreatedAt time.Time `json:"created_at"`
	UpdatedAt time.Time `json:"updated_at"`
}

type Group struct {
	ID        uint      `gorm:"primaryKey" json:"id"`
	Name      string    `json:"name"`
	Desks     []Desk    `gorm:"foreignKey:GroupID" json:"desks"`
	CreatedAt time.Time `json:"created_at"`
	UpdatedAt time.Time `json:"updated_at"`
}

type Desk struct {
	ID            uint       `gorm:"primaryKey" json:"id"`
	NumberOfSeats int        `json:"number_of_seats"`
	NrOfSeats     int        `json:"nrOfSeats"`
	Description   string     `json:"description"`
	X             float64    `json:"x"`
	Y             float64    `json:"y"`
	GroupID       *uint      `json:"group_id"`
	JoinedAt      *time.Time `json:"joined_at"`
	CreatedAt     time.Time  `json:"created_at"`
	UpdatedAt     time.Time  `json:"updated_at"`
}

// Bemeneti adatstruktúra az egyszeri asztal létrehozásához (form submit)
type CreateDeskRequest struct {
	NumberOfSeats int    `json:"number_of_seats" binding:"required"`
	Description   string `json:"description"`
}

// Bemeneti adatstruktúra a tömeges canvas pozíció mentéshez
type CanvasDeskItem struct {
	ID        *uint   `json:"id"`
	X         float64 `json:"x"`
	Y         float64 `json:"y"`
	NrOfSeats int     `json:"nrOfSeats" binding:"required,min=1,max=10"`
}

type CanvasStoreRequest struct {
	Desks []CanvasDeskItem `json:"desks" binding:"required"`
}

// ==========================================
// 2. FŐPROGRAM ÉS VÉGPONTOK
// ==========================================

func main() {
	dsn := os.Getenv("DB_URL")
	if dsn == "" {
		dsn = "desk_user:desk_password@tcp(127.0.0.1:3307)/cafe_desks_db?charset=utf8mb4&parseTime=True&loc=Local"
	}

	db, err := gorm.Open(mysql.Open(dsn), &gorm.Config{})
	if err != nil {
		log.Fatal("Failed to connect to database: ", err)
	}

	db.AutoMigrate(&Room{}, &Group{}, &Desk{})

	router := gin.Default()

	// 1. Aggregált adat az Index nézethez és az API index végponthoz
	router.GET("/api/desk-management-data", func(c *gin.Context) {
		var rooms []Room
		var groups []Group
		var standaloneDesks []Desk

		db.Find(&rooms)
		db.Preload("Desks").Find(&groups)
		db.Where("joined_at IS NULL").Find(&standaloneDesks)

		c.JSON(http.StatusOK, gin.H{
			"desksProp": gin.H{
				"groups":          groups,
				"standaloneDesks": standaloneDesks,
			},
			"rooms": rooms,
		})
	})

	// 2. Szobák lekérése (Create nézethez)
	router.GET("/api/rooms", func(c *gin.Context) {
		var rooms []Room
		db.Find(&rooms)
		c.JSON(http.StatusOK, rooms)
	})

	// 3. Egyetlen asztal létrehozása (form submit)
	router.POST("/api/desks", func(c *gin.Context) {
		var req CreateDeskRequest
		if err := c.ShouldBindJSON(&req); err != nil {
			c.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
			return
		}

		desk := Desk{
			NumberOfSeats: req.NumberOfSeats,
			Description:   req.Description,
		}

		if err := db.Create(&desk).Error; err != nil {
			c.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to create desk"})
			return
		}

		c.JSON(http.StatusCreated, desk)
	})

	// 4. Canvas pozíciók tömeges mentése (updateOrCreate x, y, nrOfSeats alapján)
	router.POST("/api/desks/canvas", func(c *gin.Context) {
		var req CanvasStoreRequest
		if err := c.ShouldBindJSON(&req); err != nil {
			c.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
			return
		}

		saved := make([]Desk, 0, len(req.Desks))
		for _, item := range req.Desks {
			var desk Desk
			if item.ID != nil && *item.ID != 0 {
				db.First(&desk, *item.ID)
			}
			desk.X = item.X
			desk.Y = item.Y
			desk.NrOfSeats = item.NrOfSeats
			if err := db.Save(&desk).Error; err != nil {
				c.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to save desk"})
				return
			}
			saved = append(saved, desk)
		}

		c.JSON(http.StatusOK, gin.H{
			"message": "Data received successfully",
			"data":    saved,
		})
	})

	// 5. Egyetlen asztal törlése
	router.DELETE("/api/desks/:id", func(c *gin.Context) {
		id := c.Param("id")
		if err := db.Delete(&Desk{}, id).Error; err != nil {
			c.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to delete desk"})
			return
		}
		c.JSON(http.StatusOK, gin.H{"message": "Desk deleted successfully"})
	})

	// 6. Összes asztal törlése
	router.DELETE("/api/desks", func(c *gin.Context) {
		if err := db.Where("1 = 1").Delete(&Desk{}).Error; err != nil {
			c.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to delete all desks"})
			return
		}
		c.JSON(http.StatusOK, gin.H{"message": "All desks deleted successfully"})
	})

	// 7. Új konfiguráció: csoportok és társítások törlése, asztalok megtartása
	router.POST("/api/desks/new-config", func(c *gin.Context) {
		var groups []Group
		db.Preload("Desks").Find(&groups)

		for _, group := range groups {
			// joined_at és group_id nullázása az érintett asztalokon
			db.Model(&Desk{}).Where("group_id = ?", group.ID).Updates(map[string]interface{}{
				"joined_at": nil,
				"group_id":  nil,
			})
			db.Delete(&group)
		}

		var standaloneDesks []Desk
		db.Where("joined_at IS NULL").Find(&standaloneDesks)

		c.JSON(http.StatusOK, gin.H{
			"desksProp": gin.H{
				"groups":          []interface{}{},
				"standaloneDesks": standaloneDesks,
			},
			"rooms": []interface{}{},
		})
	})

	router.Run(":8002")
}
