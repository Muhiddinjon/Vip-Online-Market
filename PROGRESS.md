# VIP Online Market — Ish Holati

## ✅ Qilingan ishlar

### Admin Panel
- **CategoryResource** — multilingual (UZ/EN/TR), fallback rendering, sort, status
- **ProductResource** — multilingual, rasm yuklash (`product_images`), restaurant+kategoriya tanlash
- **RestaurantResource** — xarita, logo/cover upload, User atomik yaratish (transaction)
- **UserResource** — `moderator` roli olib tashlandi (role: admin/restaurant/courier/customer)
- **CourierResource** — vehicle type, avatar, status boshqaruv
- **OrderResource** — faqat ko'rish (create yo'q)

### Restaurant Panel
- **Alohida auth guard** — admin va restoran bir vaqtda login bo'lishi mumkin (403 fix)
- **ProductResource** — o'z restaurant_id bilan scope, global kategoriyalar, rasm yuklash
- **OrderResource** — status: pending→confirmed→preparing→ready, bekor qilish
- **RestaurantStats widget** — `restaurant_id` muammosi tuzatildi

### Bug Fixlar
- Filament v4 `visible()` → `->dehydratedWhenHidden()` (multilingual fieldlar saqlanmay qolish muammosi)
- `getStateUsing(fn ($r) =>` → `fn ($record) =>` (Filament v4 closure parametr nomi)
- Kategoriyalar global qilindi (`restaurant_id` olib tashlandi, migration bajarildi)
- Category dropdown `mapWithKeys` fallback (UZ → EN → TR → '—')

### Git
- Repo init, 163 fayl commit, GitHub ga push (`main` branch)

---

## 🔲 Qilinishi kerak bo'lgan ishlar

### Restaurant Panel (davom)
- [ ] Yangi buyurtmalar avtomatik ko'rinishi — `getPollingInterval(): '10s'` (ListOrders, faqat restoran uchun)
- [ ] Restoran profili — o'z ma'lumotlarini ko'rish/tahrirlash (ism, logo, manzil, ish vaqti)
- [ ] Dashboard statistikasi — kunlik daromad, eng ko'p sotilgan mahsulotlar

### Admin Panel
- [ ] Buyurtma tafsilotlari — OrderItem lar ko'rish (qaysi mahsulot, nechta, narxi)
- [ ] Restoran statistikasi — har bir restoran bo'yicha buyurtmalar, daromad

### API (Mobil App uchun)
- [ ] `GET /api/restaurants` — restoranlar ro'yxati
- [ ] `GET /api/restaurants/{id}/products` — restoran mahsulotlari
- [ ] `GET /api/categories` — global kategoriyalar
- [ ] `POST /api/orders` — buyurtma berish
- [ ] `GET /api/orders/my` — mijozning buyurtmalari
- [ ] `GET /api/orders/{id}` — buyurtma tafsilotlari

### SMS Integratsiya
- [ ] OTP real SMS yuborish (Eskiz.uz yoki Twilio) — hozir faqat DB ga yoziladi

### Xavfsizlik
- [ ] OTP endpointlarga rate limiting (brute force himoyasi)

### Kichik ishlar
- [ ] Mahsulot rasmini table da ko'rsatish (thumbnail column)
- [ ] Kategoriya rasmini qo'shish imkoniyati
