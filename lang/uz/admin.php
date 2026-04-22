<?php

return [

    // Navigation groups & labels
    'nav' => [
        'group_menu'       => 'Menyu',
        'group_management' => 'Boshqaruv',
        'group_orders'     => 'Buyurtmalar',
        'categories'       => 'Kategoriyalar',
        'products'         => 'Mahsulotlar',
        'restaurants'      => 'Restoranlar',
        'couriers'         => 'Kuryerlar',
        'users'            => 'Foydalanuvchilar',
        'orders'           => 'Buyurtmalar',
        'dashboard'        => 'Boshqaruv paneli',
    ],

    // Common actions & labels
    'common' => [
        'status'       => 'Holat',
        'active'       => 'Faol',
        'inactive'     => 'Nofaol',
        'blocked'      => 'Bloklangan',
        'edit'         => 'Tahrirlash',
        'delete'       => 'O\'chirish',
        'force_delete' => 'Butunlay o\'chirish',
        'restore'      => 'Tiklash',
        'block'        => 'Bloklash',
        'activate'     => 'Faollashtirish',
        'save'         => 'Saqlash',
        'cancel'       => 'Bekor qilish',
        'created_at'   => 'Qo\'shilgan',
        'deleted_at'   => 'O\'chirilgan',
        'trashed'      => 'O\'chirilganlar',
        'search'       => 'Qidirish',
    ],

    // Category
    'category' => [
        'label'      => 'Kategoriya',
        'create'     => 'Kategoriya qo\'shish',
        'section_title' => 'Kategoriya',
        'name_uz'    => 'Nomi (UZ)',
        'name_en'    => 'Name (EN)',
        'name_tr'    => 'İsim (TR)',
        'sort_order' => 'Tartib',
        'products'   => 'Mahsulotlar',
    ],

    // User
    'user' => [
        'name'              => 'Ism',
        'phone'             => 'Telefon',
        'role'              => 'Rol',
        'password'          => 'Parol',
        'password_hint'     => 'O\'zgartirmaslik uchun bo\'sh qoldiring',
        'create'            => 'Foydalanuvchi qo\'shish',
        'role_admin'        => 'Admin',
        'role_restaurant'   => 'Restoran egasi',
        'role_courier'      => 'Kuryer',
        'role_customer'     => 'Mijoz',
    ],

    // Courier
    'courier' => [
        'create'            => 'Kuryer qo\'shish',
        'section_personal'  => 'Shaxsiy ma\'lumotlar',
        'section_details'   => 'Kuryer ma\'lumotlari',
        'name'              => 'Ism Familiya',
        'phone'             => 'Telefon',
        'vehicle_type'      => 'Transport turi',
        'vehicle_bike'      => 'Velosiped',
        'vehicle_scooter'   => 'Scooter',
        'vehicle_car'       => 'Avtomobil',
        'vehicle_other'     => 'Boshqa',
        'plate_number'      => 'Davlat raqami',
        'avatar'            => 'Rasm',
        'status_available'  => 'Mavjud',
        'status_busy'       => 'Band',
        'status_offline'    => 'Offline',
        'account'           => 'Hisob',
        'orders'            => 'Buyurtmalar',
    ],

    // Restaurant
    'restaurant' => [
        'label'              => 'Restoran',
        'create'             => 'Restoran qo\'shish',
        'section_login'      => 'Login ma\'lumotlari',
        'section_main'       => 'Asosiy ma\'lumotlar',
        'section_description'=> 'Tavsif',
        'section_address'    => 'Joylashuv',
        'section_images'     => 'Rasmlar',
        'name'               => 'Restoran nomi',
        'address'            => 'Manzil',
        'cover'              => 'Cover rasm',
        'orders'             => 'Buyurtmalar',
    ],

    // Order
    'order' => [
        'label'       => 'Buyurtma',
        'status_pending'    => 'Kutilmoqda',
        'status_confirmed'  => 'Qabul qilindi',
        'status_preparing'  => 'Tayyorlanmoqda',
        'status_ready'      => 'Tayyor',
        'status_delivering' => 'Yetkazilmoqda',
        'status_delivered'  => 'Yetkazildi',
        'status_cancelled'  => 'Bekor qilindi',
        'customer'    => 'Mijoz',
        'restaurant'  => 'Restoran',
        'courier'     => 'Kuryer',
        'total'       => 'Summa',
        'payment'     => 'To\'lov',
        'payment_cash'=> 'Naqd',
        'payment_card'=> 'Karta',
        'address'     => 'Manzil',
        'date'        => 'Sana',
    ],

    // Product
    'product' => [
        'create'          => 'Mahsulot qo\'shish',
        'label_uz'        => 'Mahsulot (UZ)',
        'section_restaurant'=> 'Restoran & Kategoriya',
        'section_name'    => 'Nomi va Tavsif',
        'section_image'        => 'Rasm',
        'section_images'       => 'Rasmlar',
        'section_price'        => 'Narx & Sozlamalar',
        'desc_uz'              => 'Tavsif (UZ)',
        'desc_en'              => 'Description (EN)',
        'desc_tr'              => 'Açıklama (TR)',
        'image'                => 'Mahsulot rasmi',
        'images'               => 'Rasmlar (max 3)',
        'price'                => 'Narxi (so\'m)',
        'original_price'       => 'Eski narx (so\'m)',
        'original_price_hint'  => 'Ustidan chizilgan narx ko\'rsatish uchun',
        'unit'            => 'Birlik',
        'unit_dona'       => 'Dona',
        'unit_porsiya'    => 'Porsiya',
        'unit_gramm'      => 'Gramm',
        'unit_litr'       => 'Litr',
        'available'       => 'Mavjud',
    ],

    // Stats
    'stats' => [
        'today_orders'     => 'Bugungi buyurtmalar',
        'pending_orders'   => 'Kutilayotgan',
        'active_restaurants'=> 'Faol restoranlar',
        'available_couriers'=> 'Mavjud kuryerlar',
        'monthly_revenue'  => 'Oylik daromad',
        'today_total'      => 'Jami bugun',
        'now_pending'      => 'Hozir pending',
        'working'          => 'Ishlamoqda',
        'online_free'      => 'Online va bo\'sh',
        'cancelled_not'    => 'Bekor qilinmagan',
        'in_progress'      => 'Bajarilishi kutilmoqda',
        'total'            => 'Jami',
    ],

];
