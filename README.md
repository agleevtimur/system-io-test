# Запуск проекта

```
git clone https://github.com/agleevtimur/system-io-test.git
cd .../system-io-test
docker-compose up -d --build
```

# Примеры запросов
```
curl --location --request GET 'http://localhost:8001/calculate-price' \
--header 'Content-Type: application/json' \
--data '{
    "product": 1,
    "taxNumber": "DE123456789",
    "couponCode": "D15"
}'

curl --location 'http://localhost:8001/purchase' \
--header 'Content-Type: application/json' \
--data '{
    "product": 1,
    "taxNumber": "IT12345678900",
    "couponCode": "D15",
    "paymentProcessor": "paypal"
}'

curl --location 'http://localhost:8001/purchase' \
--header 'Content-Type: application/json' \
--data '{
    "product": 1,
    "taxNumber": "IT12345678900",
    "paymentProcessor": "paypal"
}'
```

# Дополнительно

+ Выполнены все пункты из "Будет плюсом", кроме покоммитного этапа реализации. Забыл про этот пункт
+ По умолчанию БД заполняется набором тестовых данных из test_data.php (отключить можно в Dockerfile: убрать команду "bin/console app:db-fill")

