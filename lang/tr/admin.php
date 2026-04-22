<?php

return [

    // Navigation groups & labels
    'nav' => [
        'group_menu'       => 'Menü',
        'group_management' => 'Yönetim',
        'group_orders'     => 'Siparişler',
        'categories'       => 'Kategoriler',
        'products'         => 'Ürünler',
        'restaurants'      => 'Restoranlar',
        'couriers'         => 'Kuryeler',
        'users'            => 'Kullanıcılar',
        'orders'           => 'Siparişler',
        'dashboard'        => 'Yönetim Paneli',
    ],

    // Common actions & labels
    'common' => [
        'status'       => 'Durum',
        'active'       => 'Aktif',
        'inactive'     => 'Pasif',
        'blocked'      => 'Engellendi',
        'edit'         => 'Düzenle',
        'delete'       => 'Sil',
        'force_delete' => 'Kalıcı Sil',
        'restore'      => 'Geri Yükle',
        'block'        => 'Engelle',
        'activate'     => 'Etkinleştir',
        'save'         => 'Kaydet',
        'cancel'       => 'İptal',
        'created_at'   => 'Eklenme tarihi',
        'deleted_at'   => 'Silinme tarihi',
        'trashed'      => 'Silinenler',
        'search'       => 'Ara',
    ],

    // Category
    'category' => [
        'label'         => 'Kategori',
        'create'        => 'Kategori Ekle',
        'section_title' => 'Kategori',
        'name_uz'       => 'Adı (UZ)',
        'name_en'       => 'Name (EN)',
        'name_tr'       => 'İsim (TR)',
        'sort_order'    => 'Sıralama',
        'products'      => 'Ürünler',
    ],

    // User
    'user' => [
        'name'            => 'Ad',
        'phone'           => 'Telefon',
        'role'            => 'Rol',
        'password'        => 'Şifre',
        'password_hint'   => 'Değiştirmemek için boş bırakın',
        'create'          => 'Kullanıcı Ekle',
        'role_admin'      => 'Admin',
        'role_restaurant' => 'Restoran Sahibi',
        'role_courier'    => 'Kurye',
        'role_customer'   => 'Müşteri',
    ],

    // Courier
    'courier' => [
        'create'            => 'Kurye Ekle',
        'section_personal'  => 'Kişisel Bilgiler',
        'section_details'   => 'Kurye Bilgileri',
        'name'              => 'Ad Soyad',
        'phone'             => 'Telefon',
        'vehicle_type'      => 'Araç Türü',
        'vehicle_bike'      => 'Bisiklet',
        'vehicle_scooter'   => 'Scooter',
        'vehicle_car'       => 'Araba',
        'vehicle_other'     => 'Diğer',
        'plate_number'      => 'Plaka',
        'avatar'            => 'Fotoğraf',
        'status_available'  => 'Müsait',
        'status_busy'       => 'Meşgul',
        'status_offline'    => 'Çevrimdışı',
        'account'           => 'Hesap',
        'orders'            => 'Siparişler',
    ],

    // Restaurant
    'restaurant' => [
        'label'               => 'Restoran',
        'create'              => 'Restoran Ekle',
        'section_login'       => 'Giriş Bilgileri',
        'section_main'        => 'Temel Bilgiler',
        'section_description' => 'Açıklama',
        'section_address'     => 'Konum',
        'section_images'      => 'Görseller',
        'name'                => 'Restoran Adı',
        'address'             => 'Adres',
        'cover'               => 'Kapak Görseli',
        'orders'              => 'Siparişler',
    ],

    // Order
    'order' => [
        'label'             => 'Sipariş',
        'status_pending'    => 'Bekliyor',
        'status_confirmed'  => 'Onaylandı',
        'status_preparing'  => 'Hazırlanıyor',
        'status_ready'      => 'Hazır',
        'status_delivering' => 'Teslim ediliyor',
        'status_delivered'  => 'Teslim edildi',
        'status_cancelled'  => 'İptal edildi',
        'customer'          => 'Müşteri',
        'restaurant'        => 'Restoran',
        'courier'           => 'Kurye',
        'total'             => 'Tutar',
        'payment'           => 'Ödeme',
        'payment_cash'      => 'Nakit',
        'payment_card'      => 'Kart',
        'address'           => 'Adres',
        'date'              => 'Tarih',
    ],

    // Product
    'product' => [
        'create'             => 'Ürün Ekle',
        'label_uz'           => 'Ürün (UZ)',
        'section_restaurant' => 'Restoran & Kategori',
        'section_name'       => 'Ad ve Açıklama',
        'section_image'        => 'Görsel',
        'section_images'       => 'Görseller',
        'section_price'        => 'Fiyat & Ayarlar',
        'desc_uz'              => 'Açıklama (UZ)',
        'desc_en'              => 'Description (EN)',
        'desc_tr'              => 'Açıklama (TR)',
        'image'                => 'Ürün Görseli',
        'images'               => 'Görseller (maks 3)',
        'price'                => 'Fiyat',
        'original_price'       => 'Eski Fiyat',
        'original_price_hint'  => 'Üstü çizili fiyat olarak gösterilir',
        'unit'               => 'Birim',
        'unit_dona'          => 'Adet',
        'unit_porsiya'       => 'Porsiyon',
        'unit_gramm'         => 'Gram',
        'unit_litr'          => 'Litre',
        'available'          => 'Mevcut',
    ],

    // Stats
    'stats' => [
        'today_orders'      => 'Bugünkü Siparişler',
        'pending_orders'    => 'Bekleyenler',
        'active_restaurants'=> 'Aktif Restoranlar',
        'available_couriers'=> 'Müsait Kuryeler',
        'monthly_revenue'   => 'Aylık Gelir',
        'today_total'       => 'Bugün Toplam',
        'now_pending'       => 'Şu an bekleyen',
        'working'           => 'Çalışıyor',
        'online_free'       => 'Online ve müsait',
        'cancelled_not'     => 'İptal edilmemiş',
        'in_progress'       => 'İşlemde',
        'total'             => 'Toplam',
    ],

];
