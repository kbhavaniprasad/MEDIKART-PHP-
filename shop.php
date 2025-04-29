<?php
session_start();

// Generate a CSRF token if it doesn't exist
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop - MediKart</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #f4f7fa;
            color: #333;
            line-height: 1.6;
        }

        header {
            background: linear-gradient(90deg, #00c4b4, #00e6cc);
            padding: 20px 40px;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 28px;
            font-weight: 700;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 30px;
        }

        nav ul li a {
            color: #fff;
            text-decoration: none;
            font-size: 16px;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        nav ul li a:hover {
            color: #f0f8ff;
        }

        .shop-section {
            max-width: 1200px;
            margin: 60px auto;
            padding: 0 20px;
        }

        .shop-section h1 {
            font-size: 36px;
            color: #00c4b4;
            text-align: center;
            margin-bottom: 40px;
            text-transform: uppercase;
        }

        .products {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
        }

        .product-card {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0, 230, 204, 0.2);
        }

        .product-card img {
            width: 140px;
            height: 140px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 15px;
        }

        .product-card h3 {
            font-size: 18px;
            color: #333;
            margin-bottom: 10px;
        }

        .product-card p {
            font-size: 14px;
            color: #666;
            margin-bottom: 15px;
        }

        .product-card .price {
            font-size: 16px;
            font-weight: 600;
            color: #00c4b4;
            margin-bottom: 15px;
        }

        .product-card button {
            padding: 10px 20px;
            background: #00e6cc;
            color: #fff;
            border: none;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.3s ease;
        }

        .product-card button:hover {
            background: #00c4b4;
            transform: translateY(-3px);
        }

        footer {
            background: #1a2a44;
            color: #fff;
            padding: 40px 20px;
            text-align: center;
        }

        footer p {
            font-size: 14px;
            margin-bottom: 10px;
        }

        footer a {
            color: #00e6cc;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        footer a:hover {
            color: #00c4b4;
        }

        @media (max-width: 768px) {
            .nav-container {
                flex-direction: column;
                gap: 20px;
            }

            nav ul {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
        }

        
        .search input {
            width: 200px;
            height: 40px;
            border-radius: 10px;
            border: 2px solid #000;
            padding: 10px 40px 10px 15px; /* Add right padding for the icon */
            font-size: 16px;
        }

        .header h1{
            display: inline-block;
            margin: 0;
        }
        .header{
            display: flex;
            align-items: center;
            gap: 500px;
            padding: 20px;
        }
        #txtHint {
            margin-top: 10px;
            margin-bottom : 15px;
        }

        .search-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 20px;
            color: #000;
            pointer-events: none; /* Clicking icon won't block input */
    }

        .search {
            position: relative;
        }
    </style>
</head>
<body>
    <header>
        <div class="nav-container">
            <div class="logo">MediKart</div>
            <nav>
                <ul>
                    <li><a href="home1.html">Home</a></li>
                    <li><a href="shop.html">Shop</a></li>
                    <li><a href="about.html">About</a></li>
                    <li><a href="contact.php">Contact</a></li>
                    <li><a href="logout.php">LogOut</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="shop-section">
        <div class="header">
            <h1>Shop Medicines</h1>
            <div class="search">
                <input type="text" name="search" id="search" onkeyup="showmedicine(this.value)" required placeholder="Search Here">
                <span class="search-icon">🔍</span> 
            </div>
        </div>
        <div id="txtHint"></div>
        <div class="products">
            <div class="product-card">
                <img src="images/20250428_1626_Paracetamol Tablets Display_simple_compose_01jsy0jss7fabahbkkr3r3fgmq.png" alt="Paracetamol">
                <h3>Paracetamol 500mg</h3>
                <p>Pain relief and fever reduction</p>
                <div class="price">$2.99</div>
                <button class="add-to-cart-btn">Add to Cart</button>
            </div>
            <div class="product-card">
                <img src="images/20250428_1634_Amoxicillin 250mg Tablets_simple_compose_01jsy11e10enmbyyzwabz282tt.png" alt="Amoxicillin">
                <h3>Amoxicillin 250mg</h3>
                <p>Antibiotic for bacterial infections</p>
                <div class="price">$5.49</div>
                <button class="add-to-cart-btn">Add to Cart</button>
            </div>
            <div class="product-card">
                <img src="images/20250428_1636_Cetirizine 10mg Tablets_simple_compose_01jsy14pgtf3s90svrms3apdf3.png" alt="Cetirizine">
                <h3>Cetirizine 10mg</h3>
                <p>Allergy relief</p>
                <div class="price">$3.99</div>
                <button class="add-to-cart-btn">Add to Cart</button>
            </div>
            <div class="product-card">
                <img src="images/ChatGPT Image Apr 28, 2025, 04_49_46 PM.png" alt="Ciprofloxacin">
                <h3>Ciprofloxacin 500mg</h3>
                <p>Antibiotic for urinary tract infections</p>
                <div class="price">$7.25</div>
                <button class="add-to-cart-btn">Add to Cart</button>
            </div>
            
            <div class="product-card">
                <img src="images/ChatGPT Image Apr 28, 2025, 04_51_43 PM.png" alt="Loratadine">
                <h3>Loratadine 10mg</h3>
                <p>Antihistamine for allergy relief</p>
                <div class="price">$4.75</div>
                <button class="add-to-cart-btn">Add to Cart</button>
            </div>
            
            <div class="product-card">
                <img src="images/ChatGPT Image Apr 28, 2025, 04_58_48 PM.png" alt="Aspirin">
                <h3>Aspirin 100mg</h3>
                <p>Anti-inflammatory and pain relief</p>
                <div class="price">$2.99</div>
                <button class="add-to-cart-btn">Add to Cart</button>
            </div>
            <div class="product-card">
                <img src="images/ChatGPT Image Apr 28, 2025, 05_01_05 PM.png" alt="Atorvastatin">
                <h3>Atorvastatin 20mg</h3>
                <p>Cholesterol-lowering medication</p>
                <div class="price">$11.50</div>
                <button class="add-to-cart-btn">Add to Cart</button>
            </div>
            
            <div class="product-card">
                <img src="images/ChatGPT Image Apr 28, 2025, 05_03_48 PM.png" alt="Metformin">
                <h3>Metformin 500mg</h3>
                <p>Diabetes medication to control blood sugar</p>
                <div class="price">$8.10</div>
                <button class="add-to-cart-btn">Add to Cart</button>
            </div>
            <div class="product-card">
                <img src="images/ChatGPT Image Apr 28, 2025, 05_05_45 PM.png" alt="Insulin">
                <h3>Insulin 100u</h3>
                <p>For the management of diabetes</p>
                <div class="price">$20.00</div>
                <button class="add-to-cart-btn">Add to Cart</button>
            </div>
            
            <div class="product-card">
                <img src="images/ChatGPT Image Apr 28, 2025, 05_06_29 PM.png" alt="Vitamin C">
                <h3>Vitamin C 500mg</h3>
                <p>Supports immune function and skin health</p>
                <div class="price">$6.50</div>
                <button class="add-to-cart-btn">Add to Cart</button>
            </div>
            
            <div class="product-card">
                <img src="images/ChatGPT Image Apr 29, 2025, 12_05_43 PM.png" alt="Omeprazole">
                <h3>Omeprazole 20mg</h3>
                <p>Proton pump inhibitor for acid reflux</p>
                <div class="price">$8.99</div>
                <button class="add-to-cart-btn">Add to Cart</button>
            </div>
            
            <div class="product-card">
                <img src="images/ChatGPT Image Apr 29, 2025, 12_06_18 PM.png" alt="Clopidogrel">
                <h3>Clopidogrel 75mg</h3>
                <p>Blood thinner for heart disease prevention</p>
                <div class="price">$14.75</div>
                <button class="add-to-cart-btn">Add to Cart</button>
            </div>
            
            <div class="product-card">
                <img src="images/ChatGPT Image Apr 29, 2025, 12_16_15 PM.png" alt="Cephalexin">
                <h3>Cephalexin 500mg</h3>
                <p>Antibiotic for skin and respiratory infections</p>
                <div class="price">$9.00</div>
                <button class="add-to-cart-btn">Add to Cart</button>
            </div>
            
            <div class="product-card">
                <img src="images/Apr 29, 2025, 12_19_01 PM.png" alt="Furosemide">
                <h3>Furosemide 40mg</h3>
                <p>Diuretic used for fluid retention and swelling</p>
                <div class="price">$5.20</div>
                <button class="add-to-cart-btn">Add to Cart</button>
            </div>
            
            <div class="product-card">
                <img src="images/ChatGPT Image Apr 29, 2025, 12_21_14 PM.png" alt="Lisinopril">
                <h3>Lisinopril 10mg</h3>
                <p>Used for high blood pressure and heart failure</p>
                <div class="price">$7.50</div>
                <button class="add-to-cart-btn">Add to Cart</button>
            </div>
            
            <div class="product-card">
                <img src="images/ChatGPT Image Apr 29, 2025, 12_28_58 PM.png" alt="Levothyroxine">
                <h3>Levothyroxine 50mcg</h3>
                <p>Used to treat hypothyroidism (low thyroid hormone)</p>
                <div class="price">$10.25</div>
                <button class="add-to-cart-btn">Add to Cart</button>
            </div>
            
            <div class="product-card">
                <img src="images/ChatGPT Image Apr 29, 2025, 12_23_55 PM.png" alt="Albuterol">
                <h3>Albuterol 90mcg</h3>
                <p>Bronchodilator for asthma and other lung conditions</p>
                <div class="price">$12.50</div>
                <button class="add-to-cart-btn">Add to Cart</button>
            </div>
            
            <div class="product-card">
                <img src="images/20250429_1227_Loratadine Medicine Image_simple_compose_01jt059nbqf41rmpd1ke5sra7y.png" alt="Loratadine">
                <h3>Loratadine 10mg</h3>
                <p>Antihistamine for allergy relief</p>
                <div class="price">$4.50</div>
                <button class="add-to-cart-btn">Add to Cart</button>
            </div>
            
            <div class="product-card">
                <img src="images/20250429_1229_Montelukast 10mg Medication_simple_compose_01jt05d6meecktywwmtew8x38n.png" alt="Montelukast">
                <h3>Montelukast 10mg</h3>
                <p>Used to prevent asthma attacks and allergies</p>
                <div class="price">$9.99</div>
                <button class="add-to-cart-btn">Add to Cart</button>
            </div>
            
            <div class="product-card">
                <img src="images/ChatGPT Image Apr 29, 2025, 12_31_47 PM.png" alt="Bupropion">
                <h3>Bupropion 150mg</h3>
                <p>Antidepressant and smoking cessation aid</p>
                <div class="price">$15.00</div>
                <button class="add-to-cart-btn">Add to Cart</button>
            </div>
            
            <div class="product-card">
                <img src="images/20250429_1231_Paroxetine 20mg Tablet_simple_compose_01jt05hd5tetfanmqn54e8g7vt.png" alt="Paroxetine">
                <h3>Paroxetine 20mg</h3>
                <p>Used for depression, anxiety, and OCD</p>
                <div class="price">$18.25</div>
                <button class="add-to-cart-btn">Add to Cart</button>
            </div>
            
            <div class="product-card">
                <img src="images/ChatGPT Image Apr 29, 2025, 12_38_41 PM.png" alt="Levofloxacin">
                <h3>Levofloxacin 500mg</h3>
                <p>Antibiotic for respiratory and urinary tract infections</p>
                <div class="price">$10.75</div>
                <button class="add-to-cart-btn">Add to Cart</button>
            </div>
            
        </div>
    </section>
    <script>
        const csrfToken = '<?php echo $_SESSION['csrf_token']; ?>';

        function showmedicine(str) { 
            if (str.length === 0) {
                document.getElementById("txtHint").innerHTML = "";
                return;
            } else {
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState === 4 && this.status === 200) {
                        document.getElementById("txtHint").innerHTML = this.responseText;
                    }
                };
                xmlhttp.open("GET", "medicine.php?q=" + str, true);
                xmlhttp.send();
            }
        }

        document.addEventListener("DOMContentLoaded", function () {
            const addToCartButtons = document.querySelectorAll(".add-to-cart-btn");

            addToCartButtons.forEach(button => {
                button.addEventListener("click", function () {
                    const productCard = button.closest('.product-card');
                    const productName = productCard.querySelector('h3').textContent;
                    const productPrice = productCard.querySelector('.price').textContent;

                    // Create a form dynamically to send data to PHP
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = 'addtocart.php';

                    // Add product name
                    const nameInput = document.createElement('input');
                    nameInput.type = 'hidden';
                    nameInput.name = 'productName';
                    nameInput.value = productName;
                    form.appendChild(nameInput);

                    // Add product price
                    const priceInput = document.createElement('input');
                    priceInput.type = 'hidden';
                    priceInput.name = 'productPrice';
                    priceInput.value = productPrice;
                    form.appendChild(priceInput);

                    // Add CSRF token
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = 'csrf_token';
                    csrfInput.value = csrfToken; // Use the server-generated token
                    form.appendChild(csrfInput);

                    document.body.appendChild(form);
                    form.submit();

                    // Show alert
                    alert('Item added to Cart');

                    // Optionally update button text
                    button.textContent = "Added!";
                    button.disabled = true;

                    // Reset after 1.5 seconds
                    setTimeout(() => {
                        button.textContent = "Add to Cart";
                        button.disabled = false;
                    }, 1500);
                });
            });
        });
    </script>
        

    <footer>
        <p>© 2025 MediKart. All rights reserved.</p>
        <p><a href="contact.php">Contact Us</a> | <a href="#privacy">Privacy Policy</a></p>
    </footer>
</body>
</html>

