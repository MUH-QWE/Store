<<<<<<< HEAD
# نظام السلة (Cart System)

## نظرة عامة
تم تطوير نظام سلة تسوق متقدم يعمل بطريقتين:
1. **LocalStorage** للزوار (Guest Users)
2. **Database** للمستخدمين المسجلين (Logged-in Users)

## الميزات الرئيسية

### 1. التخزين المزدوج (Dual Storage)
- **للزوار**: يتم حفظ السلة في `localStorage` تحت المفتاح `guest_cart`
- **للمستخدمين المسجلين**: يتم حفظ السلة في قاعدة البيانات عبر API

### 2. المزامنة التلقائية (Auto Sync)
عندما يقوم زائر بتسجيل الدخول:
- يتم تلقائياً نقل جميع المنتجات من `localStorage` إلى قاعدة البيانات
- يتم حذف السلة المحلية بعد المزامنة
- لا يفقد المستخدم أي منتجات أضافها قبل تسجيل الدخول

### 3. التحديث الفوري (Real-time Updates)
- عداد السلة في الـ Header يتحدث تلقائياً
- صفحة السلة تتحدث فورياً عند أي تغيير
- استخدام نظام Subscription للتحديثات

## البنية التقنية

### الملفات الرئيسية

#### 1. `web/js/cartManager.js`
الملف الأساسي الذي يدير جميع عمليات السلة:

```javascript
class CartManager {
    // تحميل السلة من localStorage أو API
    loadCart()
    
    // إضافة منتج للسلة
    add(product, variantId, quantity)
    
    // حذف منتج من السلة
    remove(cartItemId)
    
    // تحديث كمية منتج
    updateQuantity(cartItemId, newQty)
    
    // مسح السلة بالكامل
    clear()
    
    // مزامنة السلة عند تسجيل الدخول/الخروج
    handleAuthChange()
}
```

#### 2. `web/Products.html`
صفحة المنتجات مع زر "Add to Cart":
- يعمل للزوار والمستخدمين المسجلين
- يمرر بيانات المنتج بشكل آمن
- يستخدم `cartManager.add()` مباشرة

#### 3. `web/Cart.html`
صفحة السلة مع:
- عرض جميع المنتجات
- أزرار زيادة/تقليل الكمية
- زر حذف المنتج
- حساب المجموع الكلي

#### 4. `web/js/header.js`
الـ Header مع:
- عداد السلة (Cart Badge)
- يتحدث تلقائياً عند أي تغيير

## كيفية العمل

### للزوار (Guest Users)
```
1. المستخدم يضيف منتج
   ↓
2. cartManager.add() يتحقق: لا يوجد token
   ↓
3. يضيف المنتج إلى array في الذاكرة
   ↓
4. يحفظ في localStorage.setItem('guest_cart', ...)
   ↓
5. يحدث الواجهة عبر notify()
```

### للمستخدمين المسجلين (Logged-in Users)
```
1. المستخدم يضيف منتج
   ↓
2. cartManager.add() يتحقق: يوجد token
   ↓
3. يرسل POST request إلى /cart/add.php
   ↓
4. API يحفظ في قاعدة البيانات
   ↓
5. يعيد تحميل السلة من API
   ↓
6. يحدث الواجهة عبر notify()
```

### عند تسجيل الدخول (Login Sync)
```
1. المستخدم يسجل دخول
   ↓
2. يتم trigger لـ 'auth-change' event
   ↓
3. cartManager.handleAuthChange() يتنفذ
   ↓
4. يقرأ guest_cart من localStorage
   ↓
5. لكل منتج: يرسل POST إلى /cart/add.php
   ↓
6. يحذف localStorage.removeItem('guest_cart')
   ↓
7. يحمل السلة من API
```

## API Endpoints المستخدمة

### 1. GET `/cart/get.php`
- يجلب جميع منتجات السلة للمستخدم المسجل
- يتطلب: Authorization token
- يرجع: array من cart items مع تفاصيل المنتجات

### 2. POST `/cart/add.php`
- يضيف منتج للسلة أو يزيد الكمية
- Body: `{ product_id, variant_id?, quantity? }`
- يتطلب: Authorization token

### 3. POST `/cart/update.php`
- يحدث كمية منتج في السلة
- Body: `{ id: cart_item_id, quantity }`
- يتطلب: Authorization token

### 4. POST `/cart/delete.php`
- يحذف منتج من السلة
- Body: `{ id: cart_item_id }`
- يتطلب: Authorization token

### 5. POST `/cart/clear.php`
- يمسح السلة بالكامل
- يتطلب: Authorization token

## بنية البيانات

### في localStorage (للزوار)
```json
[
  {
    "cart_item_id": "local_1703001234567",
    "product_id": 5,
    "variant_id": null,
    "quantity": 2,
    "name": "Product Name",
    "price": 29.99,
    "image": "/uploads/product.jpg"
  }
]
```

### من API (للمستخدمين المسجلين)
```json
[
  {
    "cart_item_id": 123,
    "product_id": 5,
    "variant_id": null,
    "quantity": 2,
    "name": "Product Name",
    "price": 29.99,
    "image": "/uploads/product.jpg",
    "variant_name": null,
    "variant_value": null,
    "price_adjustment": 0
  }
]
```

## الاستخدام في الصفحات

### إضافة cartManager لأي صفحة جديدة
```html
<script src="js/ALL.js"></script>
<script src="js/cartManager.js"></script>
<script src="js/layout.js"></script>
<script src="js/header.js"></script>
```

### إضافة منتج للسلة
```javascript
const product = {
    id: 5,
    name: "Product Name",
    price: 29.99,
    image: "/uploads/product.jpg"
};

cartManager.add(product, null, 1); // null = no variant, 1 = quantity
```

### الاشتراك في تحديثات السلة
```javascript
cartManager.subscribe((cart) => {
    console.log('Cart updated:', cart);
    // تحديث الواجهة هنا
});
```

## الأمان

1. **للزوار**: البيانات محلية فقط، لا ترسل للسيرفر
2. **للمستخدمين المسجلين**: جميع الطلبات تتطلب Authorization token
3. **التحقق من الصلاحيات**: API يتحقق من هوية المستخدم قبل أي عملية
4. **XSS Protection**: استخدام JSON.stringify و proper escaping

## الملاحظات المهمة

1. **Cart Item ID**: 
   - للزوار: `local_` + timestamp
   - للمستخدمين: رقم من قاعدة البيانات

2. **Variant Support**: النظام يدعم المتغيرات (variants) مثل الألوان والأحجام

3. **Price Adjustments**: يتم حساب تعديلات السعر للمتغيرات تلقائياً

4. **Error Handling**: جميع العمليات تتضمن try-catch و error logging

## التطوير المستقبلي

- [ ] إضافة Wishlist بنفس الطريقة
- [ ] دعم الكوبونات والخصومات
- [ ] حفظ السلة للمستخدمين المسجلين عبر الأجهزة
- [ ] إشعارات عند تغيير السعر
- [ ] حد أقصى للكمية المتاحة
=======
# نظام السلة (Cart System)

## نظرة عامة
تم تطوير نظام سلة تسوق متقدم يعمل بطريقتين:
1. **LocalStorage** للزوار (Guest Users)
2. **Database** للمستخدمين المسجلين (Logged-in Users)

## الميزات الرئيسية

### 1. التخزين المزدوج (Dual Storage)
- **للزوار**: يتم حفظ السلة في `localStorage` تحت المفتاح `guest_cart`
- **للمستخدمين المسجلين**: يتم حفظ السلة في قاعدة البيانات عبر API

### 2. المزامنة التلقائية (Auto Sync)
عندما يقوم زائر بتسجيل الدخول:
- يتم تلقائياً نقل جميع المنتجات من `localStorage` إلى قاعدة البيانات
- يتم حذف السلة المحلية بعد المزامنة
- لا يفقد المستخدم أي منتجات أضافها قبل تسجيل الدخول

### 3. التحديث الفوري (Real-time Updates)
- عداد السلة في الـ Header يتحدث تلقائياً
- صفحة السلة تتحدث فورياً عند أي تغيير
- استخدام نظام Subscription للتحديثات

## البنية التقنية

### الملفات الرئيسية

#### 1. `web/js/cartManager.js`
الملف الأساسي الذي يدير جميع عمليات السلة:

```javascript
class CartManager {
    // تحميل السلة من localStorage أو API
    loadCart()
    
    // إضافة منتج للسلة
    add(product, variantId, quantity)
    
    // حذف منتج من السلة
    remove(cartItemId)
    
    // تحديث كمية منتج
    updateQuantity(cartItemId, newQty)
    
    // مسح السلة بالكامل
    clear()
    
    // مزامنة السلة عند تسجيل الدخول/الخروج
    handleAuthChange()
}
```

#### 2. `web/Products.html`
صفحة المنتجات مع زر "Add to Cart":
- يعمل للزوار والمستخدمين المسجلين
- يمرر بيانات المنتج بشكل آمن
- يستخدم `cartManager.add()` مباشرة

#### 3. `web/Cart.html`
صفحة السلة مع:
- عرض جميع المنتجات
- أزرار زيادة/تقليل الكمية
- زر حذف المنتج
- حساب المجموع الكلي

#### 4. `web/js/header.js`
الـ Header مع:
- عداد السلة (Cart Badge)
- يتحدث تلقائياً عند أي تغيير

## كيفية العمل

### للزوار (Guest Users)
```
1. المستخدم يضيف منتج
   ↓
2. cartManager.add() يتحقق: لا يوجد token
   ↓
3. يضيف المنتج إلى array في الذاكرة
   ↓
4. يحفظ في localStorage.setItem('guest_cart', ...)
   ↓
5. يحدث الواجهة عبر notify()
```

### للمستخدمين المسجلين (Logged-in Users)
```
1. المستخدم يضيف منتج
   ↓
2. cartManager.add() يتحقق: يوجد token
   ↓
3. يرسل POST request إلى /cart/add.php
   ↓
4. API يحفظ في قاعدة البيانات
   ↓
5. يعيد تحميل السلة من API
   ↓
6. يحدث الواجهة عبر notify()
```

### عند تسجيل الدخول (Login Sync)
```
1. المستخدم يسجل دخول
   ↓
2. يتم trigger لـ 'auth-change' event
   ↓
3. cartManager.handleAuthChange() يتنفذ
   ↓
4. يقرأ guest_cart من localStorage
   ↓
5. لكل منتج: يرسل POST إلى /cart/add.php
   ↓
6. يحذف localStorage.removeItem('guest_cart')
   ↓
7. يحمل السلة من API
```

## API Endpoints المستخدمة

### 1. GET `/cart/get.php`
- يجلب جميع منتجات السلة للمستخدم المسجل
- يتطلب: Authorization token
- يرجع: array من cart items مع تفاصيل المنتجات

### 2. POST `/cart/add.php`
- يضيف منتج للسلة أو يزيد الكمية
- Body: `{ product_id, variant_id?, quantity? }`
- يتطلب: Authorization token

### 3. POST `/cart/update.php`
- يحدث كمية منتج في السلة
- Body: `{ id: cart_item_id, quantity }`
- يتطلب: Authorization token

### 4. POST `/cart/delete.php`
- يحذف منتج من السلة
- Body: `{ id: cart_item_id }`
- يتطلب: Authorization token

### 5. POST `/cart/clear.php`
- يمسح السلة بالكامل
- يتطلب: Authorization token

## بنية البيانات

### في localStorage (للزوار)
```json
[
  {
    "cart_item_id": "local_1703001234567",
    "product_id": 5,
    "variant_id": null,
    "quantity": 2,
    "name": "Product Name",
    "price": 29.99,
    "image": "/uploads/product.jpg"
  }
]
```

### من API (للمستخدمين المسجلين)
```json
[
  {
    "cart_item_id": 123,
    "product_id": 5,
    "variant_id": null,
    "quantity": 2,
    "name": "Product Name",
    "price": 29.99,
    "image": "/uploads/product.jpg",
    "variant_name": null,
    "variant_value": null,
    "price_adjustment": 0
  }
]
```

## الاستخدام في الصفحات

### إضافة cartManager لأي صفحة جديدة
```html
<script src="js/ALL.js"></script>
<script src="js/cartManager.js"></script>
<script src="js/layout.js"></script>
<script src="js/header.js"></script>
```

### إضافة منتج للسلة
```javascript
const product = {
    id: 5,
    name: "Product Name",
    price: 29.99,
    image: "/uploads/product.jpg"
};

cartManager.add(product, null, 1); // null = no variant, 1 = quantity
```

### الاشتراك في تحديثات السلة
```javascript
cartManager.subscribe((cart) => {
    console.log('Cart updated:', cart);
    // تحديث الواجهة هنا
});
```

## الأمان

1. **للزوار**: البيانات محلية فقط، لا ترسل للسيرفر
2. **للمستخدمين المسجلين**: جميع الطلبات تتطلب Authorization token
3. **التحقق من الصلاحيات**: API يتحقق من هوية المستخدم قبل أي عملية
4. **XSS Protection**: استخدام JSON.stringify و proper escaping

## الملاحظات المهمة

1. **Cart Item ID**: 
   - للزوار: `local_` + timestamp
   - للمستخدمين: رقم من قاعدة البيانات

2. **Variant Support**: النظام يدعم المتغيرات (variants) مثل الألوان والأحجام

3. **Price Adjustments**: يتم حساب تعديلات السعر للمتغيرات تلقائياً

4. **Error Handling**: جميع العمليات تتضمن try-catch و error logging

## التطوير المستقبلي

- [ ] إضافة Wishlist بنفس الطريقة
- [ ] دعم الكوبونات والخصومات
- [ ] حفظ السلة للمستخدمين المسجلين عبر الأجهزة
- [ ] إشعارات عند تغيير السعر
- [ ] حد أقصى للكمية المتاحة
>>>>>>> e27ec8f7fb7c7231818d1513b72f8cc50b28affd
