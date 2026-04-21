<?php

return [

    // Navigation groups & labels
    'nav' => [
        'group_menu'       => 'Menu',
        'group_management' => 'Management',
        'group_orders'     => 'Orders',
        'categories'       => 'Categories',
        'products'         => 'Products',
        'restaurants'      => 'Restaurants',
        'couriers'         => 'Couriers',
        'users'            => 'Users',
        'orders'           => 'Orders',
        'dashboard'        => 'Dashboard',
    ],

    // Common actions & labels
    'common' => [
        'status'       => 'Status',
        'active'       => 'Active',
        'inactive'     => 'Inactive',
        'blocked'      => 'Blocked',
        'edit'         => 'Edit',
        'delete'       => 'Delete',
        'force_delete' => 'Permanently Delete',
        'restore'      => 'Restore',
        'block'        => 'Block',
        'activate'     => 'Activate',
        'save'         => 'Save',
        'cancel'       => 'Cancel',
        'created_at'   => 'Created',
        'deleted_at'   => 'Deleted',
        'trashed'      => 'Trashed',
        'search'       => 'Search',
    ],

    // Category
    'category' => [
        'label'         => 'Category',
        'create'        => 'Add Category',
        'section_title' => 'Category',
        'name_uz'       => 'Name (UZ)',
        'name_en'       => 'Name (EN)',
        'name_tr'       => 'Name (TR)',
        'sort_order'    => 'Sort Order',
        'products'      => 'Products',
    ],

    // User
    'user' => [
        'name'            => 'Name',
        'phone'           => 'Phone',
        'role'            => 'Role',
        'password'        => 'Password',
        'password_hint'   => 'Leave blank to keep unchanged',
        'create'          => 'Add User',
        'role_admin'      => 'Admin',
        'role_restaurant' => 'Restaurant Owner',
        'role_courier'    => 'Courier',
        'role_customer'   => 'Customer',
    ],

    // Courier
    'courier' => [
        'create'            => 'Add Courier',
        'section_personal'  => 'Personal Details',
        'section_details'   => 'Courier Details',
        'name'              => 'Full Name',
        'phone'             => 'Phone',
        'vehicle_type'      => 'Vehicle Type',
        'vehicle_bike'      => 'Bicycle',
        'vehicle_scooter'   => 'Scooter',
        'vehicle_car'       => 'Car',
        'vehicle_other'     => 'Other',
        'plate_number'      => 'Plate Number',
        'avatar'            => 'Photo',
        'status_available'  => 'Available',
        'status_busy'       => 'Busy',
        'status_offline'    => 'Offline',
        'account'           => 'Account',
        'orders'            => 'Orders',
    ],

    // Restaurant
    'restaurant' => [
        'label'               => 'Restaurant',
        'create'              => 'Add Restaurant',
        'section_login'       => 'Login Details',
        'section_main'        => 'Basic Information',
        'section_description' => 'Description',
        'section_address'     => 'Location',
        'section_images'      => 'Images',
        'name'                => 'Restaurant Name',
        'address'             => 'Address',
        'cover'               => 'Cover Image',
        'orders'              => 'Orders',
    ],

    // Order
    'order' => [
        'label'             => 'Order',
        'status_pending'    => 'Pending',
        'status_confirmed'  => 'Confirmed',
        'status_preparing'  => 'Preparing',
        'status_ready'      => 'Ready',
        'status_delivering' => 'Delivering',
        'status_delivered'  => 'Delivered',
        'status_cancelled'  => 'Cancelled',
        'customer'          => 'Customer',
        'restaurant'        => 'Restaurant',
        'courier'           => 'Courier',
        'total'             => 'Total',
        'payment'           => 'Payment',
        'payment_cash'      => 'Cash',
        'payment_card'      => 'Card',
        'address'           => 'Address',
        'date'              => 'Date',
    ],

    // Product
    'product' => [
        'create'             => 'Add Product',
        'label_uz'           => 'Product (UZ)',
        'section_restaurant' => 'Restaurant & Category',
        'section_name'       => 'Name & Description',
        'section_image'      => 'Image',
        'section_price'      => 'Price & Settings',
        'desc_uz'            => 'Description (UZ)',
        'desc_en'            => 'Description (EN)',
        'desc_tr'            => 'Description (TR)',
        'image'              => 'Product Image',
        'price'              => 'Price',
        'unit'               => 'Unit',
        'unit_dona'          => 'Piece',
        'unit_porsiya'       => 'Portion',
        'unit_gramm'         => 'Gram',
        'unit_litr'          => 'Litre',
        'available'          => 'Available',
    ],

    // Stats
    'stats' => [
        'today_orders'      => "Today's Orders",
        'pending_orders'    => 'Pending',
        'active_restaurants'=> 'Active Restaurants',
        'available_couriers'=> 'Available Couriers',
        'monthly_revenue'   => 'Monthly Revenue',
        'today_total'       => 'Total today',
        'now_pending'       => 'Pending now',
        'working'           => 'Working',
        'online_free'       => 'Online & free',
        'cancelled_not'     => 'Not cancelled',
        'in_progress'       => 'In progress',
        'total'             => 'Total',
    ],

];
