Commission Fee Calculator

    This PHP application processes financial operations from a CSV file and calculates commission fees based on specific rules for private and business clients.

Running the Script

    Prerequisites
        PHP >= 8.0

        Composer (for autoloading)

    Installation
        Clone or download the project.

        Install dependencies:
            composer install

    Execute the script
        Use the following command:
            php script.php path/to/your/file.csv
        Example:
            php script.php input.csv
        The script will read the operations from input.csv, calculate the commission fees, and output the results line by line.

Run Automated Tests
    A test is included to validate the expected behavior with the provided sample data.

    To run the tests:
        composer run test
    The tests will process the given operations and check that the output matches the expected results.

Project Structure
    src/Service/CommissionCalculator.php → Commission fee calculation logic.

    src/Service/ExchangeRateService.php → Fetches exchange rates.

    src/Service/CsvReader.php → Reads CSV files.

    src/Model/Operation.php → Represents a financial operation.


Notes

    No framework is used, only pure PHP and Composer for autoloading.

    No temporary files or databases are used.

    The project follows PSR-4 autoloading and PSR-12 coding standards.

Example input

    Contents of an example input.csv file:

        2014-12-31,4,private,withdraw,1200.00,EUR
        2015-01-01,4,private,withdraw,1000.00,EUR


Author
Developed by [Ismail Azzarraa].