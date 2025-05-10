<?php
require 'connectdatabase.php'; 
$products = [
    ['Study Table', 'A versatile study table designed for modern workspaces, featuring ample surface area and storage options for books and gadgets.', 500, 'small study table.jpg', 200, 'ALL'],
    ['Dining Table', 'A sleek and sturdy dining table that provides a perfect spot for family meals, made from premium materials for durability.', 20000, 'dining table.jpeg', 150, 'Kitchen'],
    ['Aquarium Stand', 'A stable and stylish aquarium stand that accommodates small to medium-sized tanks, designed for both aesthetics and functionality.', 1500, 'aquorium.jpg', 30, 'ALL'],
    ['Gaming Chair', 'An ergonomic gaming chair designed for long gaming sessions, featuring adjustable armrests and a comfortable reclining function.', 15500, 'gaming chair.webp', 25, 'ALL'],
    ['Comfortable chair', 'A compact and comfortable chair perfect for long hours of sitting, with padded seating and supportive backrest.', 5500, 'new_product_img_1.png', 50, 'ALL'],
    ['Chaise Longue', 'A luxurious chaise longue for relaxation, with a sleek design and soft cushioning, ideal for lounging or reading.', 3000, 'featured_deals_img_2.png', 10, 'Drawing Room'],
    ['Cupboard', 'A spacious and functional cupboard that provides ample storage space, perfect for organizing clothes and accessories in any room.', 17500, 'featured_deals_img_3.png', 18, 'Kitchen'],
    ['Wood Bar Stool', 'A high-quality wood bar stool with a comfortable seat, perfect for home bars or kitchen counters.', 1000, 'featured_deals_img_4.png', 12, 'Kitchen'],
    ['Sofa', 'A plush sofa designed for comfort and style, featuring high-density foam cushions and soft upholstery for your living room.', 19000, 'featured_deals_img_1.png', 25, 'Drawing Room'],
    ['BedRoom Decoration', 'A decorative set for your bedroom that includes stylish accessories like lamps, vases, and frames to add personality to your space.', 2500, 'new_product_img_2.png', 22, 'Drawing Room'],
    ['Decor for Drawing Room', 'A complete set of decorative items for your drawing room, including elegant furniture and eye-catching art pieces to enhance your living space.', 500, 'new_product_img_4.png', 16, 'Drawing Room'],
    ['Sofa With Table', 'A compact sofa set with a built-in coffee table, perfect for smaller living rooms or apartments, offering both style and practicality.', 35500, 'sofa with table.png', 8, 'ALL'],
    ['Computer Table', 'A modern computer table with a minimalist design, perfect for both office and home use. Includes a dedicated area for a keyboard and mouse.',6500, 'computer table.jpg', 14, 'ALL'],
    ['Center Table', 'A sophisticated center table with a glass top and a sleek design that complements any living room or office setup.', 3500, 'center table.webp', 12, 'Drawing Room'],
    ['Bed', 'A luxurious bed with a sturdy frame and a plush mattress, designed for maximum comfort and durability for a restful sleep.', 3500, 'bed.jpg', 7, 'ALL'],
    ['Shelf Tower', 'A stylish shelf tower that provides ample storage for books, decorations, and small appliances, ideal for any room in the house.', 7500, 'shelf.jpg', 9, 'Drawing Room'],
    ['Makeup Table', 'A practical and elegant makeup table with a large mirror, designed to keep your beauty essentials organized and easily accessible.', 6500, 'makeuo Table.jpg', 20, 'Kitchen'],
    ['Office Chair', 'An ergonomic office chair with adjustable height and lumbar support, ideal for long hours of work or study at your desk.', 6500, 'office chair.webp', 11, 'ALL'],
    ['Cloth Cupboard', 'A durable cloth cupboard that offers quick and easy storage for clothes, shoes, and other essentials, perfect for small spaces.', 12500, 'clothvupboard.webp', 13, 'ALL'],
    ['Single Bed', 'A comfortable and compact single bed, ideal for children’s rooms or guest rooms, with a sturdy frame and comfortable mattress.', 1500, 'single bed.jpg', 6, 'ALL'],
];
$category_ids = [];
$result = $conn->query("SELECT id, name FROM categories");
while ($row = $result->fetch_assoc()) {
    $category_ids[$row['name']] = $row['id'];
}
$stmt = $conn->prepare("INSERT INTO products (name, description, price, image, stock, category_id) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssisis", $name, $description, $price, $image, $stock, $category_id);
foreach ($products as $product) {
    $name = $product[0];
    $description = $product[1];
    $price = $product[2];
    $image = $product[3];
    $stock = $product[4];
    $category_name = $product[5];
    
    // Get the category_id based on the category name
    $category_id = isset($category_ids[$category_name]) ? $category_ids[$category_name] : null;
    
    // Ensure category_id exists before inserting the product
    if ($category_id !== null) {
        $stmt->execute();
    } else {
        echo "Category '{$category_name}' not found.\n";
    }
}

$stmt->close();
$conn->close();

echo "Products inserted successfully!";
?>
