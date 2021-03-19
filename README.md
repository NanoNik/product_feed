# Тестовое задание, генерация фида для facebook

## Запустить:
```
git clone https://github.com/NanoNik/product_feed
sudo docker-compose run --rm php composer update --prefer-dist
sudo docker-compose run --rm php composer install

Создать таблицы:
sudo docker-compose run --rm php ./yii migrate

Сгенерировать новый магазин и рандомные товары для фида:
sudo docker-compose run --rm php ./yii random 1000

Поднимаем всё:
sudo docker-compose up -d
```

Роуты:
* Управление списоком магазинов: http://127.0.0.1:8000/stores (GET, POST, DELETE, etc)

Пример POST запроса:
```
{
  "title": "Some store title",
  "description": "Some store description",
  "link": "https://google.com/",
  "feedUrl": "https://google.com/feed"
}
```

* Управление списком продуктов: http://127.0.0.1:8000/products (GET, POST, DELETE, etc)

Пример POST запроса:

```
{
  "store_id": 1,
  "uuid": "SOME-PRODUCT-ID",
  "availability": "in stock",
  "condition": "new",
  "description": "Our cool product!",
  "image_link": "https://klike.net/uploads/posts/2019-07/1564314090_3.jpg",
  "link": "https://site.com/catalog/product",
  "title": "Lorem ipsum",
  "price": "12.5 USD",
  "brand": "Super Brand",
  "additional_image_link": "https://img.site.com/img1.png,https://img.site.com/img2.png",
  "age_group": "adult",
  "color": "green",
  "gender": "unisex",
  "google_product_category": "Home & Garden > Flood, Fire & Gas Safety",
  "sale_price": "12.0 USD",
  "sale_price_effective_date": 1616133104
}
```

* Получение фида http://127.0.0.1:8000/feed/<shop_id> (GET)

Пример для магазина созданного командой random:

```
wget -O feed.xml http://127.0.0.1:8000/feed/1
```
