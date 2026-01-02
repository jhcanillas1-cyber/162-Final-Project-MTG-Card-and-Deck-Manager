# MTG Deck Builder & Collection Manager

A robust Laravel-based web application for Magic: The Gathering (MTG) enthusiasts to manage their physical collections and prototype new decks using live data from the **Scryfall API**.



by Jean Oscar Philippe H. Canillas for LIS 162.


## 🚀 Key Features

### 🔍 Card Browser
* **Live Import:** Fetch card data directly from Scryfall by set code.
* **Advanced Filters:** Search by set (e.g., `MOM`, `NEO`), card name, or price.
* **Rich UI:** Paginated grid view with high-quality card imagery and real-time USD/PHP pricing.

### 📦 Collection Management
* **Personal Inventory:** Maintain a single persistent collection per user.
* **Quick Add:** Add cards to your inventory directly from the browser.
* **Quantity Tracking:** Manage and view owned quantities at a glance.

### 🎴 Deck Builder
* **Multi-Deck Support:** Create and name multiple decks.
* **Collection Integration:** Build decks using cards you already own.
* **Easy Export:** One-click export to `.txt` format compatible with **MTG Arena** and tabletop play.

---

https://github.com/user-attachments/assets/c446f64e-823c-41c9-8ab9-5a4f2728728d

---
## 🛠️ Tech Stack

| Layer | Technology |
| :--- | :--- |
| **Backend** | Laravel 10 (PHP) |
| **Frontend** | Blade Templates + Bootstrap 5 |
| **Database** | MySQL |
| **Authentication** | Laravel Breeze |
| **API** | Scryfall API |

---

## 📂 Project Structure

```text
app/
├── Models/
│   ├── Collection.php       # User inventory logic
│   ├── CollectionCard.php   # Pivot data (quantities)
│   ├── Deck.php             # Deck metadata
│   ├── DeckCard.php         # Cards within a specific deck
│   └── ScryfallCard.php     # Local cache of API card data
├── Http/Controllers/
│   ├── CardController.php       # Browser and Scryfall logic
│   ├── CollectionController.php # Inventory management
│   └── DeckController.php       # Builder and Export logic
resources/views/
├── layouts/html.blade.php   # Base template
├── cards/                   # Card browser views
├── collections/             # Inventory views
└── decks/                   # Builder and Export views
