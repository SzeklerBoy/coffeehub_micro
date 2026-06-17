# ☕ CoffeeHub - Cloud-Native Microservices Architecture

---

## 🏗️ Architecture and Services

The system consists of 4 independent microservices, separated according to strict **service boundary** principles. Each service has an isolated technology stack and its own database.

| Microservice      | Directory             | Technology        | Responsibility                                 | Network (K8s)           |
| :---------------- | :-------------------- | :---------------- | :--------------------------------------------- | :---------------------- |
| **API Gateway**   | `laravel-gateway/`    | PHP / Laravel     | Entry point, Frontend, Request routing         | 🌐 Public (Ingress)     |
| **Menu Service**  | `menu-fastapi/`       | Python / FastAPI  | Management of products, prices, and categories | 🌐 Public (Ingress)     |
| **Desk Service**  | `desk-service-go/`    | Go (Golang)       | Management of table status and occupancy       | 🔒 Internal (ClusterIP) |
| **Order Service** | `order-service-node/` | Node.js / Express | Order intake and processing                    | 🔒 Internal (ClusterIP) |

---
