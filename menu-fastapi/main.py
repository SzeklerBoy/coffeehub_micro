import os
from fastapi import FastAPI, Depends, HTTPException
from sqlalchemy import create_engine, Column, Integer, String, Float, ForeignKey, DateTime, func, or_
from sqlalchemy.ext.declarative import declarative_base
from sqlalchemy.orm import sessionmaker, Session, relationship
from pydantic import BaseModel
from typing import List, Optional

SQLALCHEMY_DATABASE_URL =  os.getenv("DB_URL" ,  "mysql+pymysql://menu_user:menu_password@127.0.0.1:3310/cafe_menu_db")

engine = create_engine(SQLALCHEMY_DATABASE_URL)
SessionLocal  = sessionmaker(autocommit=False, autoflush=False, bind=engine)
Base = declarative_base()

class Language(Base):
    __tablename__ = "languages"

    id = Column(Integer, primary_key=True, index=True)
    name = Column(String(255), nullable=False)
    code = Column(String(255), nullable=False, unique=True)
    created_at = Column(DateTime, default=func.now())
    updated_at = Column(DateTime, default=func.now(), onupdate=func.now())

class MenuItem(Base):
    __tablename__ = "menu_items"

    id = Column(Integer, primary_key=True, index=True)
    quantity = Column(Float, nullable=False)
    price = Column(Float, nullable=False)
    image_path = Column(String(255), nullable=True)
    ETAinMinutes = Column(Float, nullable=True)
    created_at = Column(DateTime, default=func.now())
    updated_at = Column(DateTime, default=func.now(), onupdate=func.now())

    translations = relationship("Translation", back_populates="menu_item")

class Translation(Base):
    __tablename__ = "translations"

    id = Column(Integer, primary_key=True, index=True)
    menu_item_id = Column(Integer, ForeignKey("menu_items.id", ondelete="CASCADE"), nullable=False)
    language_id = Column(Integer, ForeignKey("languages.id", ondelete="CASCADE"), nullable=False)
    name = Column(String(255), nullable=False)
    description = Column(String(255), nullable=True)
    category = Column(String(255), nullable=False)
    created_at = Column(DateTime, default=func.now())
    updated_at = Column(DateTime, default=func.now(), onupdate=func.now())

    menu_item = relationship("MenuItem", back_populates="translations")
    language = relationship("Language")
    
class TranslationSchema(BaseModel):
    id: int
    language_id: int
    name: str
    description: Optional[str] = None
    category: str

    class Config:
        orm_mode = True

class MenuItemSchema(BaseModel):
    id: int
    quantity: float
    price: float
    image_path: Optional[str] = None
    ETAinMinutes: Optional[float] = None
    translations: List[TranslationSchema] = [] # Include translations in the MenuItem schema

    class Config:
        orm_mode = True
        
class MenuItemFlatSchema(BaseModel):
    id: int
    quantity: float
    price: float
    image_path: Optional[str] = None
    ETAinMinutes: Optional[float] = None
    name: str
    description: Optional[str] = None
    category: str

    class Config:
        orm_mode = True

class MenuItemCreate(BaseModel):
    quantity: float
    price: float
    image_path: Optional[str] = None
    ETAinMinutes: Optional[float] = None
    locale: str
    name: str
    description: Optional[str] = None
    category: str

class MenuItemUpdate(BaseModel):
    quantity: Optional[float] = None
    price: Optional[float] = None
    image_path: Optional[str] = None
    ETAinMinutes: Optional[float] = None
    locale: str
    name: Optional[str] = None
    description: Optional[str] = None
    category: Optional[str] = None

Base.metadata.create_all(bind=engine)        

app = FastAPI(title="CoffeeHub Menu API")

def get_db():
    db = SessionLocal()
    try:
        yield db
    finally:
        db.close()
        
@app.get("/api/menuitems", response_model=List[MenuItemFlatSchema])
def get_menu_items(
    locale: str = "en",
    search: Optional[str] = None,
    ids: Optional[str] = None,
    db: Session = Depends(get_db)
):
    language = db.query(Language).filter(Language.code == locale).first()
    language_id = language.id if language else 1

    query = db.query(MenuItem, Translation).join(Translation, MenuItem.id == Translation.menu_item_id)
    query = query.filter(Translation.language_id == language_id)

    if search:
        search_term = f"%{search}%"
        query = query.filter(
            or_(
                Translation.name.ilike(search_term),
                Translation.description.ilike(search_term),
                Translation.category.ilike(search_term)
            )
        )

    if ids:
        id_list = [int(i) for i in ids.split(',') if i.strip().isdigit()]
        if id_list:
            query = query.filter(MenuItem.id.in_(id_list))

    results = query.all()

    formatted_items = []
    for item, translation in results:
        formatted_items.append({
            "id": item.id,
            "quantity": item.quantity,
            "price": item.price,
            "image_path": item.image_path,
            "ETAinMinutes": item.ETAinMinutes,
            "name": translation.name,
            "description": translation.description,
            "category": translation.category,
        })

    return formatted_items

@app.get("/api/menuitems/{menu_item_id}", response_model=MenuItemSchema)
def get_menu_item(menu_item_id: int, db: Session = Depends(get_db)):
    menu_item = db.query(MenuItem).filter(MenuItem.id == menu_item_id).first()
    if not menu_item:
        raise HTTPException(status_code=404, detail="Menu item not found")
    return menu_item

@app.get("/api/categories", response_model=List[str])
def get_distinct_categories(locale: str = "en", db: Session = Depends(get_db)):
    """
    Visszaadja az egyedi kategóriákat a megadott nyelv alapján.
    """
    language = db.query(Language).filter(Language.code == locale).first()
    
    if not language:
        return []

    categories = db.query(Translation.category)\
                   .filter(Translation.language_id == language.id)\
                   .distinct()\
                   .all()

    return [category[0] for category in categories]

@app.post("/api/menuitems", response_model=MenuItemFlatSchema, status_code=201)
def create_menu_item(item_data: MenuItemCreate, db: Session = Depends(get_db)):
    
    language = db.query(Language).filter(Language.code == item_data.locale).first()
    
    if not language:
        language = Language(name=item_data.locale.upper(), code=item_data.locale)
        db.add(language)
        db.commit()
        db.refresh(language)
    
    language_id = language.id

    new_item = MenuItem(
        quantity=item_data.quantity,
        price=item_data.price,
        image_path=item_data.image_path,
        ETAinMinutes=item_data.ETAinMinutes
    )
    db.add(new_item)
    db.commit()
    db.refresh(new_item)

    new_translation = Translation(
        menu_item_id=new_item.id,
        language_id=language_id, 
        name=item_data.name,
        description=item_data.description,
        category=item_data.category
    )
    db.add(new_translation)
    db.commit()

    return {
        "id": new_item.id,
        "quantity": new_item.quantity,
        "price": new_item.price,
        "image_path": new_item.image_path,
        "ETAinMinutes": new_item.ETAinMinutes,
        "name": new_translation.name,
        "description": new_translation.description,
        "category": new_translation.category,
    }
    
@app.put("/api/menuitems/{item_id}", response_model=MenuItemFlatSchema)
def update_menu_item(item_id: int, item_data: MenuItemUpdate, db: Session = Depends(get_db)):
    
    db_item = db.query(MenuItem).filter(MenuItem.id == item_id).first()
    if not db_item:
        raise HTTPException(status_code=404, detail="Menu item not found")

    if item_data.quantity is not None: db_item.quantity = item_data.quantity
    if item_data.price is not None: db_item.price = item_data.price
    if item_data.image_path is not None: db_item.image_path = item_data.image_path
    if item_data.ETAinMinutes is not None: db_item.ETAinMinutes = item_data.ETAinMinutes

    language = db.query(Language).filter(Language.code == item_data.locale).first()
    language_id = language.id if language else 1

    db_translation = db.query(Translation).filter(
        Translation.menu_item_id == item_id,
        Translation.language_id == language_id
    ).first()

    if db_translation:
        if item_data.name is not None: db_translation.name = item_data.name
        if item_data.description is not None: db_translation.description = item_data.description
        if item_data.category is not None: db_translation.category = item_data.category
    elif item_data.name: 
        new_translation = Translation(
            menu_item_id=item_id,
            language_id=language_id,
            name=item_data.name,
            description=item_data.description,
            category=item_data.category or "Uncategorized"
        )
        db.add(new_translation)

    db.commit()
    db.refresh(db_item)

    final_translation = db_translation if db_translation else new_translation
    return {
        "id": db_item.id,
        "quantity": db_item.quantity,
        "price": db_item.price,
        "image_path": db_item.image_path,
        "ETAinMinutes": db_item.ETAinMinutes,
        "name": final_translation.name,
        "description": final_translation.description,
        "category": final_translation.category,
    }

@app.delete("/api/menuitems/{item_id}", status_code=204)
def delete_menu_item(item_id: int, db: Session = Depends(get_db)):
    db_item = db.query(MenuItem).filter(MenuItem.id == item_id).first()
    if not db_item:
        raise HTTPException(status_code=404, detail="Menu item not found")
        
    if db_item.quantity > 0:
        raise HTTPException(status_code=400, detail=f"Cannot delete item because it has {db_item.quantity} in stock.")

    db.delete(db_item)
    db.commit()
    return None