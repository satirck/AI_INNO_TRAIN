document.addEventListener('DOMContentLoaded', function() {
    const expenseForm = document.getElementById('expenseForm');
    const expensesList = document.getElementById('expensesList');
    const calculateBtn = document.getElementById('calculateBtn');
    let expenses = [];

    // Load expenses from localStorage if available
    if (localStorage.getItem('expenses')) {
        expenses = JSON.parse(localStorage.getItem('expenses'));
        updateExpensesTable();
    }

    // Handle form submission
    expenseForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const category = document.getElementById('category').value.trim();
        const amount = parseFloat(document.getElementById('amount').value);

        if (category && !isNaN(amount) && amount > 0) {
            addExpense(category, amount);
            expenseForm.reset();
        }
    });

    // Add new expense
    function addExpense(category, amount) {
        expenses.push({ category, amount });
        saveExpenses();
        updateExpensesTable();
    }

    // Delete expense
    function deleteExpense(index) {
        expenses.splice(index, 1);
        saveExpenses();
        updateExpensesTable();
    }

    // Save expenses to localStorage
    function saveExpenses() {
        localStorage.setItem('expenses', JSON.stringify(expenses));
    }

    // Update expenses table
    function updateExpensesTable() {
        expensesList.innerHTML = '';
        expenses.forEach((expense, index) => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${expense.category}</td>
                <td>$${expense.amount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                <td>
                    <button class="delete-btn" onclick="deleteExpense(${index})">Delete</button>
                </td>
            `;
            expensesList.appendChild(row);
        });
    }

    // Calculate and display results
    calculateBtn.addEventListener('click', function() {
        if (expenses.length === 0) {
            alert('Please add some expenses first!');
            return;
        }

        // Calculate total amount
        const totalAmount = expenses.reduce((sum, expense) => sum + expense.amount, 0);
        
        // Calculate average daily expense (assuming 30 days)
        const averageDaily = totalAmount / 30;

        // Get top 3 largest expenses
        const topExpenses = [...expenses]
            .sort((a, b) => b.amount - a.amount)
            .slice(0, 3);

        // Update results display
        document.getElementById('totalAmount').textContent = 
            `$${totalAmount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
        
        document.getElementById('averageDaily').textContent = 
            `$${averageDaily.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;

        const topExpensesList = document.getElementById('topExpenses');
        topExpensesList.innerHTML = topExpenses.map(expense => 
            `<li>${expense.category}: $${expense.amount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</li>`
        ).join('');
    });

    // Make deleteExpense function available globally
    window.deleteExpense = deleteExpense;
}); 