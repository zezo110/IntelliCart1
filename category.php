<!--===HEADER===-->
<?php include 'inc/header.php'; ?>

<!--===FEATURED PRODUCTS===-->
<section class="featured" id="featured">
    <?php

    if (isset($_GET['category_id']) && is_numeric($_GET['category_id'])) {
        $categoryId = $_GET['category_id'];


        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "user";
        $connection = new mysqli($servername, $username, $password, $dbname);

        if ($connection->connect_error) {
            die("Connection failed: " . $connection->connect_error);
        }


        $sql = "SELECT * FROM products
                WHERE category_id = $categoryId";
        $result = $connection->query($sql);

        if ($result->num_rows > 0) {
            echo "<h2 class='section-title'>FEATURED PRODUCTS</h2>";

            // عرض المنتجات
            echo "<div class='featured__container bd-grid'>";
            while ($row = $result->fetch_assoc()) {
                echo "<div class='featured__product'>";
                echo "<div class='featured__box'>";
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
            echo "<p>No products found in this category.</p>";
        }

        $connection->close();
    } else {
        echo "<p>Invalid category ID.</p>";
    }
    ?>
</section>

<!--===FOOTER===-->
<?php include 'inc/footer.php'; ?>
<!--===FOOTER===-->
