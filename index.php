<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Calculator</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Expense Calculator</h1>
        
        <div class="expense-form">
            <h2>Add New Expense</h2>
            <form id="expenseForm">
                <div class="form-group">
                    <label for="category">Category:</label>
                    <input type="text" id="category" name="category" required>
                </div>
                <div class="form-group">
                    <label for="amount">Amount ($):</label>
                    <input type="number" id="amount" name="amount" step="0.01" required>
                </div>
                <button type="submit" class="btn">Add Expense</button>
            </form>
        </div>

        <div class="expenses-table">
            <h2>Expenses List</h2>
            <table id="expensesTable">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Amount ($)</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="expensesList">
                    <!-- Expenses will be added here dynamically -->
                </tbody>
            </table>
        </div>

        <div class="results">
            <h2>Results</h2>
            <button id="calculateBtn" class="btn">Calculate</button>
            <div id="results" class="results-container">
                <div class="result-item">
                    <h3>Total Expenses:</h3>
                    <p id="totalAmount">$0.00</p>
                </div>
                <div class="result-item">
                    <h3>Average Daily Expense:</h3>
                    <p id="averageDaily">$0.00</p>
                </div>
                <div class="result-item">
                    <h3>Top 3 Largest Expenses:</h3>
                    <ul id="topExpenses">
                        <!-- Top expenses will be listed here -->
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html> 