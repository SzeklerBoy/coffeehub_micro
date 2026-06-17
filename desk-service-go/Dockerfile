FROM golang:alpine AS builder

WORKDIR /app

COPY go.mod go.sum ./
RUN go mod download

COPY . .
RUN CGO_ENABLED=0 GOOS=linux go build -o desk-service main.go

FROM alpine:latest

WORKDIR /root/

COPY --from=builder /app/desk-service .

EXPOSE 8002

CMD ["./desk-service"]