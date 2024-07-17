	<!--===HEADER===-->
    <?php include 'inc/header.php'; ?>

	<main class="l-main">
	<section class="home" id="home">
		<div class="home__container bd-grid">
			<div class="home__data">
				<h1 class="home__title">NEW<br><span>ARRIVALS</span></h1>
				<a href="#featured" class="button">GO SHOPPING</a>
			</div>

			<!--<img src="" alt="" class="home__img"/>-->
		</div>
	</section>

	<!--===COLLECTION ===-->
	<section class="collection section">
		<div class="collection__container bd-grid">
			<div class="collection__box">
				<img src="img/backpackMan.png" alt="" class="collection__img"/>

				<div class="collection__data">
					<h2 class="collection__title"><span class="collection__subtitle">Men</span><br>Backpack</h2>
					<a href="category.php?category_id=10" class="collection__view">View collection</a>
				</div>
			</div>

			<div class="collection__box">
				<div class="collection__data">
					<h2 class="collection__title"><span class="collection__subtitle">Women</span><br>Backpack</h2>
					<a href="category.php?category_id=11" class="collection__view">View collection</a>
				</div>

				<img src="img/backpackWoman.png" alt="" class="collection__img">
			</div>
		</div>
	</section>

<!--===FEATURED PRODUCTS===-->
<section class="featured" id="featured">
    <?php
    // اتصال بقاعدة البيانات
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "user";
    $connection = new mysqli($servername, $username, $password, $dbname);

    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    // استعلام SQL لاسترجاع أحدث 4 منتجات مضافة
    $sql = "SELECT * FROM products ORDER BY id DESC LIMIT 4";
    $result = $connection->query($sql);

    if ($result->num_rows > 0) {
        echo "<h2 class='section-title'>FEATURED PRODUCTS</h2>";

        // عرض المنتجات
        echo "<div class='featured__container bd-grid'>";
        while ($row = $result->fetch_assoc()) {
            echo "<div class='featured__product'>";
            echo "<div class='featured__box'>";
            echo "<div class='featured__new'>NEW</div>";
            echo "<a href='product_detail.php?id=" . $row['id'] . "'>";
			// تحقق من وجود الصورة قبل عرضها
			if (!empty($row['image'])) {
				echo "<img src='data:image/png;base64," . base64_encode($row['image']) . "' alt='' class='featured__img'/>";
			} else {
				echo "No image available";
			}
			echo "</div>";

            echo "<div class='featured__data'>";
            echo "<h3 class='featured__name'>" . $row['PD_name'] . "</h3>";
            echo "<span class='featured__preci'>$" . $row['price'] . "</span>";
            echo "</div></a>";
            echo "</div>";
        }
        echo "</div>";
    } else {
        echo "<p>No products found.</p>";
    }

    $connection->close();
    ?>
</section>


	<!--===OFFER===-->
	<section class="offer">
		<div class="offer__bg">
			<div class="offer__data">
				<h2 class="offer__title">Special Offer</h2>
				<p class="offer__description">Special offerts discounts for women this week only</p>
				<a href="products.php" class="button">SHOP NOW</a>
			</div>
		</div>
	</section>

	<!--===NEW ARRIVALS===-->
	<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user";

// إنشاء اتصال بقاعدة البيانات
$connection = new mysqli($servername, $username, $password, $dbname);

// التحقق من الاتصال
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// استعلام لاسترجاع أحدث 6 منتجات
$query = "SELECT id, image, PD_name FROM products ORDER BY date_added DESC LIMIT 6";
$result = $connection->query($query);

// عرض النتائج
if ($result->num_rows > 0) {
    echo '<section class="new section" id="new">';
    echo '<h2 class="section-title">NEW ARRIVALS</h2>';
    echo '<a href="#" class="section-all"></a>';
    echo '<div class="new__container bd-grid">';

    while ($row = $result->fetch_assoc()) {
        echo '<div class="new__box">';
        echo '<img src="data:image/jpeg;base64,' . base64_encode($row['image']) . '" alt="" class="new__img"/>';
        echo '<div class="new__link">';
        echo '<a href="product_detail.php?id=' . $row['id'] . '" class="button">VIEW PRODUCT</a>';
        echo '</div>';
        echo '</div>';
    }

    echo '</div>';
    echo '</section>';
} else {
    echo '<p>No new arrivals found.</p>';
}

// إغلاق اتصال قاعدة البيانات
$connection->close();
?>



	
	<!--===SPONSORS===-->
	<section class="sponsors section">
		<div class="sponsors__container bd-grid">
			<div class="sponsors__logo">
				<img src="img/logo1.png" alt="">
			</div>

			<div class="sponsors__logo">
				<img src="img/logo2.png" alt="">
			</div>

			<div class="sponsors__logo">
				<img src="img/logo3.png" alt="">
			</div>

			<div class="sponsors__logo">
				<img src="img/logo4.png" alt="">
			</div>
		</div>
	</section>
	</main>

	<!--===FOOTER===-->
    <?php include 'inc/footer.php'; ?>
	<!--===FOOTER===-->
