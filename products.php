<!--===HEADER===-->
<?php include 'inc/header.php'; ?>

<!--===FEATURED PRODUCTS===-->
<section class="featured" id="featured">
    <h2 class="section-title">FEATURED PRODUCTS</h2>
    <h2 class="section-title">All PRODUCTS</h2>
    <a href="" class="section-all">Choose what suits you from our products</a>

    <div class="featured__container bd-grid">
        <?php
        // قم بالاتصال بقاعدة البيانات
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "user";
        $connection = new mysqli($servername, $username, $password, $dbname);
        if ($connection->connect_error) {
            die("Connection failed: " . $connection->connect_error);
        }

        // استعلم عن المنتجات
        $sql = "SELECT * FROM products";
        $result = $connection->query($sql);

        // قم بعرض المنتجات
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="featured__product">';
                echo '<div class="featured__box">';
                echo '<a href="product_detail.php?id=' . $row['id'] . '">';
                // تحقق من وجود الصورة قبل عرضها
                if (!empty($row['image'])) {
                    echo '<img src="data:image/png;base64,' . base64_encode($row['image']) . '" alt="" class="featured__img"/>';
                } else {
                    echo 'No image available';
                }
                echo '</a>';
                echo '</div>';
                echo '<div class="featured__data">';
                echo '<h3 class="featured__name">' . $row['PD_name'] . '</h3>';
                echo '<span class="featured__preci">$' . $row['price'] . '</span>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo 'No products available';
        }

        // أغلق الاتصال بقاعدة البيانات
        $connection->close();
        ?>
    </div>
</section>

<!--===FOOTER===-->
<?php include 'inc/footer.php'; ?>
<!--===FOOTER===-->
