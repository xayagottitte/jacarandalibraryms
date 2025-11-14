<!DOCTYPE html>
<html>
<head>
    <title>Test Category Dropdown</title>
</head>
<body>
    <h1>Category Dropdown Test</h1>
    
    <h2>Simulating Admin Create Book</h2>
    <select name="category_id" id="category_id">
        <option value="">Select Category</option>
        <?php
        require_once '../app/models/Database.php';
        require_once '../app/core/Model.php';
        require_once '../app/models/Category.php';
        
        $categoryModel = new Category();
        $categories = $categoryModel->getAllCategories();
        
        echo "<!-- Categories count: " . count($categories) . " -->\n";
        
        if (isset($categories) && is_array($categories)):
            foreach ($categories as $category):
        ?>
                <option value="<?= $category['id'] ?>">
                    <?= htmlspecialchars($category['name']) ?>
                </option>
        <?php
            endforeach;
        endif;
        ?>
    </select>
    
    <script>
        const select = document.getElementById('category_id');
        console.log('Total options:', select.options.length);
        console.log('Options:', Array.from(select.options).map(o => o.value + ': ' + o.text));
    </script>
</body>
</html>
