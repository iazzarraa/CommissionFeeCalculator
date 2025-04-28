# 💼 Commission Fee Calculator

This PHP application processes financial operations from a CSV file and calculates commission fees based on specified rules for private and business clients.

---

## 🚀 How to Run the Script

### Prerequisites
- PHP >= 8.0
- Composer (for autoloading)

### Installation Steps

1. Clone the project or download it.
2. Install dependencies:

```bash
composer install
```


## ⚡ Execute the Script

To run the script, use:

```bash
php script.php path/to/your/input.csv
```

**Example:**

```bash
php script.php input.csv
```

The script will process the operations listed in the CSV file and print the commission fees for each operation.

---

## 🧪 Run Automated Tests

The project includes a simple automated test that processes the provided example and checks the output.

To execute the tests:

```bash
composer run test
```

Make sure you have installed PHPUnit via Composer before running the tests.

---

## 📜 Project Structure

- `src/Service/CommissionCalculator.php` → Handles the calculation logic.
- `src/Service/ExchangeRateService.php` → Fetches exchange rates.
- `src/Service/CsvReader.php` → Reads and parses the CSV file.
- `src/Model/Operation.php` → Represents a financial operation.

---

## 📌 Important Notes

- No framework is used (pure PHP).
- No temporary files, external databases, or external services beyond the exchange rate API are used.
- The project follows PSR-4 autoloading and PSR-12 coding standards.
- Calculations are done fully in memory.

---

## 📈 Example Input

A sample `input.csv` should look like:

```csv
2014-12-31,4,private,withdraw,1200.00,EUR
2015-01-01,4,private,withdraw,1000.00,EUR
2016-01-05,4,private,withdraw,1000.00,EUR
...
```

---

## 🔥 Author

Developed by **Ismail Azzarraa**.

---

