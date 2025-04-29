<?php
$q = strtolower($_GET["q"]); // Get the user input and convert to lowercase

// Array of medicines (you can later move to a database)
$medicines = [
    "paracetamol" => '
    <div class="product-card">
        <img src="images/20250428_1626_Paracetamol Tablets Display_simple_compose_01jsy0jss7fabahbkkr3r3fgmq.png" alt="Paracetamol">
        <h3>Paracetamol 500mg</h3>
        <p>Pain relief and fever reduction</p>
        <div class="price">$2.99</div>
        <button>Add to Cart</button>
        
    </div>',
    
    "amoxicillin" => '
    <div class="product-card">
        <img src="images/amoxicillin.png" alt="Amoxicillin">
        <h3>Amoxicillin 250mg</h3>
        <p>Antibiotic for bacterial infections</p>
        <div class="price">$5.49</div>
        <button>Add to Cart</button>
        
    </div>',

    "ibuprofen" => '
    <div class="product-card">
        <img src="images/ibuprofen.png" alt="Ibuprofen">
        <h3>Ibuprofen 400mg</h3>
        <p>Pain relief and anti-inflammatory</p>
        <div class="price">$4.99</div>
        <button>Add to Cart</button>
    </div>',

    "Cetirizine"=>' <div class="product-card">
    <img src="images/20250428_1636_Cetirizine 10mg Tablets_simple_compose_01jsy14pgtf3s90svrms3apdf3.png" alt="Cetirizine">
    <h3>Cetirizine 10mg</h3>
    <p>Allergy relief</p>
    <div class="price">$3.99</div>
    <button>Add to Cart</button>
    </div>',

    "ciprofloxacin"=>'
    <div class="product-card">
        <img src="images/ChatGPT Image Apr 28, 2025, 04_49_46 PM.png" alt="Ciprofloxacin">
        <h3>Ciprofloxacin 500mg</h3>
        <p>Antibiotic for urinary tract infections</p>
        <div class="price">$7.25</div>
        <button>Add to Cart</button>
    </div>',

    "loratadine"=>'
    <div class="product-card">
        <img src="images/ChatGPT Image Apr 28, 2025, 04_51_43 PM.png" alt="Loratadine">
        <h3>Loratadine 10mg</h3>
        <p>Antihistamine for allergy relief</p>
        <div class="price">$4.75</div>
        <button>Add to Cart</button>
    </div>',

    "aspirin"=>'
    <div class="product-card">
        <img src="images/ChatGPT Image Apr 28, 2025, 04_58_48 PM.png" alt="Aspirin">
        <h3>Aspirin 100mg</h3>
        <p>Anti-inflammatory and pain relief</p>
        <div class="price">$2.99</div>
        <button>Add to Cart</button>
    </div>',

    "atorvastatin"=>'
    <div class="product-card">
        <img src="images/ChatGPT Image Apr 28, 2025, 05_01_05 PM.png" alt="Atorvastatin">
        <h3>Atorvastatin 20mg</h3>
        <p>Cholesterol-lowering medication</p>
        <div class="price">$11.50</div>
        <button>Add to Cart</button>
    </div>',

    "metformin" => '
    <div class="product-card">
        <img src="images/ChatGPT Image Apr 28, 2025, 05_03_48 PM.png" alt="Metformin">
        <h3>Metformin 500mg</h3>
        <p>Diabetes medication to control blood sugar</p>
        <div class="price">$8.10</div>
        <button>Add to Cart</button>
    </div>',

    "insulin" => '
    <div class="product-card">
        <img src="images/ChatGPT Image Apr 28, 2025, 05_05_45 PM.png" alt="Insulin">
        <h3>Insulin 100u</h3>
        <p>For the management of diabetes</p>
        <div class="price">$20.00</div>
        <button>Add to Cart</button>
    </div>',

    "vitamin c"=>'
    <div class="product-card">
        <img src="images/ChatGPT Image Apr 28, 2025, 05_06_29 PM.png" alt="Vitamin C">
        <h3>Vitamin C 500mg</h3>
        <p>Supports immune function and skin health</p>
        <div class="price">$6.50</div>
        <button>Add to Cart</button>
    </div>',

    "omeprazole"=>'
    <div class="product-card">
        <img src="20250428_1634_Omeprazole_20mg.png" alt="Omeprazole">
        <h3>Omeprazole 20mg</h3>
        <p>Proton pump inhibitor for acid reflux</p>
        <div class="price">$8.99</div>
        <button>Add to Cart</button>
    </div>',

    "clopidogrel"=>'
    <div class="product-card">
        <img src="20250428_1634_Clopidogrel_75mg.png" alt="Clopidogrel">
        <h3>Clopidogrel 75mg</h3>
        <p>Blood thinner for heart disease prevention</p>
        <div class="price">$14.75</div>
        <button>Add to Cart</button>
    </div>',

    "cephalexin"=>'
    <div class="product-card">
        <img src="20250428_1634_Cephalexin_500mg.png" alt="Cephalexin">
        <h3>Cephalexin 500mg</h3>
        <p>Antibiotic for skin and respiratory infections</p>
        <div class="price">$9.00</div>
        <button>Add to Cart</button>
    </div>',

    "furosemide"=>'
    <div class="product-card">
        <img src="20250428_1634_Furosemide_40mg.png" alt="Furosemide">
        <h3>Furosemide 40mg</h3>
        <p>Diuretic used for fluid retention and swelling</p>
        <div class="price">$5.20</div>
        <button>Add to Cart</button>
    </div>',

    "lisinopril"=>'
    <div class="product-card">
        <img src="20250428_1634_Lisinopril_10mg.png" alt="Lisinopril">
        <h3>Lisinopril 10mg</h3>
        <p>Used for high blood pressure and heart failure</p>
        <div class="price">$7.50</div>
        <button>Add to Cart</button>
    </div>',

    "levothyroxine"=>'
    <div class="product-card">
        <img src="20250428_1634_Levothyroxine_50mcg.png" alt="Levothyroxine">
        <h3>Levothyroxine 50mcg</h3>
        <p>Used to treat hypothyroidism (low thyroid hormone)</p>
        <div class="price">$10.25</div>
        <button>Add to Cart</button>
    </div>',

    "albuterol"=>'
    <div class="product-card">
        <img src="20250428_1634_Albuterol_90mcg.png" alt="Albuterol">
        <h3>Albuterol 90mcg</h3>
        <p>Bronchodilator for asthma and other lung conditions</p>
        <div class="price">$12.50</div>
        <button>Add to Cart</button>
    </div>',

    "loratadine"=>'
    <div class="product-card">
        <img src="20250428_1634_Loratadine_10mg.png" alt="Loratadine">
        <h3>Loratadine 10mg</h3>
        <p>Antihistamine for allergy relief</p>
        <div class="price">$4.50</div>
        <button>Add to Cart</button>
    </div>',

    "montelukast"=>'
    <div class="product-card">
        <img src="20250428_1634_Montelukast_10mg.png" alt="Montelukast">
        <h3>Montelukast 10mg</h3>
        <p>Used to prevent asthma attacks and allergies</p>
        <div class="price">$9.99</div>
        <button>Add to Cart</button>
    </div>',

    "bupropion"=>'
    <div class="product-card">
        <img src="20250428_1634_Bupropion_150mg.png" alt="Bupropion">
        <h3>Bupropion 150mg</h3>
        <p>Antidepressant and smoking cessation aid</p>
        <div class="price">$15.00</div>
        <button>Add to Cart</button>
    </div>',

    "paroxetine"=>'
    <div class="product-card">
        <img src="20250428_1634_Paroxetine_20mg.png" alt="Paroxetine">
        <h3>Paroxetine 20mg</h3>
        <p>Used for depression, anxiety, and OCD</p>
        <div class="price">$18.25</div>
        <button>Add to Cart</button>
    </div>',

    "levofloxacin"=>'
    <div class="product-card">
        <img src="20250428_1634_Levofloxacin_500mg.png" alt="Levofloxacin">
        <h3>Levofloxacin 500mg</h3>
        <p>Antibiotic for respiratory and urinary tract infections</p>
        <div class="price">$10.75</div>
        <button>Add to Cart</button>
    </div>'

    
];

// Check if medicine exists
$found = false;
foreach ($medicines as $key => $value) {
    if (strpos($key, $q) !== false) {
        echo $value;
        $found = true;
        break;
    }
}

if (!$found) {
    echo "<p>No medicine found for '<strong>" . htmlspecialchars($_GET["q"]) . "</strong>'</p>";
}
?>
