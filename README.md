# 📱 KBC HackFest Web App 🎉

A dynamic event management system built for **KBC HackFest 2025**, providing dedicated panels for **Admins, Wallet, Participants, Stalls, and Visitors**, integrated with a custom **QR Code Payment System** using Laravel.

---

## 📌 Project Overview

This web application was developed as part of the **KBC HackFest 2025** event management. The platform allows for:

* Efficient event management.
* Seamless QR-based transactions using in-event currency **KBC Points, Fest Points, Game Points**.
* Role-specific panels with individual functionalities for admins, participants, stalls, and visitors.

---

## 🚀 Features

* 🛠️ **Admin Panel**: Manage users, events, transactions, and system settings.
* 🎟️ **Participant Panel**: Event registrations, profile management, and purchase history.
* 🛒 **Stall Panel**: Product listings, sales tracking, and QR-based payment acceptance.
* 👥 **Visitor Panel**: Explore stalls, purchase with QR scanning, and transaction history.
* 💸 **QR Code Payment System**: Scan-to-pay functionality using **KBC Points, Fest Points, Game Points**.
* 📈 **Payment History & Analytics**: Track and manage transactions.
* 🔒 **Secure Authentication & Role-based Access Control**.

---

## 🛠️ Tech Stack

* **Backend**: Laravel (PHP Framework)
* **Database**: SQLite3
* **Frontend**: Blade, HTML, CSS, Tailwind CSS
* **QR Code Generator & Scanner**: `Simple QrCode` package, custom scan handler
* **Payment Module**: Custom virtual currency system (**KBC Points**)

---

## 📦 Installation

### 1️⃣ Clone the Repository:

```bash
git clone https://github.com/iccyexojv/HackFest-WebApp-Qr-Payment.git
cd HackFest-WebApp-Qr-Payment
```

### 2️⃣ Install Dependencies:

```bash
composer install
npm install
npm run dev
```

### 3️⃣ Configure Environment:

* Copy `.env.example` to `.env`
* Update database credentials and other settings.

```bash
cp .env.example .env
php artisan key:generate
```

### 4️⃣ Run Migrations:

```bash
php artisan migrate --seed
```

### 5️⃣ Start Development Server:

```bash
php artisan serve
```

---


## 📚 Usage

* Register and assign roles through Admin Panel.
* Use Stall Panel to generate product QR codes.
* Visitors/Participants scan QR codes via web-based QR scanner.
* Complete transactions using in-event **KBC Points**.
* Track and manage all records through respective dashboards.

---

## 👥 Contributors

* Sujan Shrestha — [ https://github.com/iccyexojv ]
* Mentor: Umanga Pathak 

---

