# 🎬 Movie-Ticket-Booking

A secure, lightweight, modern, and responsive Web Application built with PHP and MySQL for booking movie tickets online.

---

## 📌 Features

* **User Authentication**: User registration and login session management.
* **Dynamic Movie Selection**: Browse available movie titles, showtimes, genres and pricing.
* **Interactive Seat Picker**: Live seat grid allowing multi-seat selection with real-time status updates (Free, Selected, Booked).
* **Booking & Payment Processing**: Secure booking handling that records reserved seats per user, movie, date, and time slot.

---

## 🚀 Getting Started

### Prerequisites

Ensure you have the following installed on your environment:
* PHP >= 8.0
* MySQL Server >= 5.7 or MariaDB
* Web Server (Apache/Nginx) or XAMPP / WAMP / MAMP

---

## 📁 Project Structure

```text
movie_booking/
├── db.php                # Database connection configuration
├── style.css             # Main stylesheet for UI layout and themes
├── schema.sql            # MySQL database schema and initial data
├── index.php             # Landing / welcome page
├── login.php             # User authentication (Login)
├── register.php          # User registration
├── logout.php            # Session destruction and sign-out
├── movies.php            # Movie catalog and list view
├── seats.php             # Interactive seat selection screen
├── payment.php           # Transaction processing & double-booking protection
├── success.php           # Booking confirmation page
└──  README.md            # Project documentation for Git
