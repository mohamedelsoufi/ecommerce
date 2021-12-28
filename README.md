
* **URL**
    http://ahmedmaher1792001dgashdgywq.xyz/Hiring-Application/public/

* **Success Response:**

  * **status:** true <br />
    **message:** message <br />
    **data:** `{ id : 12, .. }` <br />
 
* **Error Response:**

  * **successful:** false <br />
    **status:** "status error" <br />
    **message:** message <br />
    
 * **status error:**
 
| status error  | meaning               |
| :---:         | :---:                 |
| E00           | default error         |
| E01           | authentication error  |
| E02           | blocked               |
| E03           | validation error      |
| E04           | not found             |
| E05           | not active            |
| E06           | expired               |


<h1 align="center"> user apis </h1>


* ** Auth end point**

| end point | Method | Auth |URL Params| Describe |
| :---:     | :---:  | :---:|:---:     | :---:    |
| /login    | Post   | No   | email : required <br /> password : required| your acount should be verify |
| /register | Post   | No   | email :  required <br /> fullName : required <br /> password : required <br /> confirm_password : required <br /> phone : required <br /> gender : required <br /> birth : required <br />  | you should verify your email <br> gender ==> (0->male, 1-> famale) |
| /logout   | Post   | yes  | --       | --       |


* ** forget Passwored end point**

| end point                 | Method | Auth |URL Params         | Describe |
| :---:                     | :---:  | :---:|:---:              | :---:    |
| /forgetPasswored/sendMail | Post   | No   | email : required  | You will receive code in your email|
|/forgetPasswored/checkCode | post   | No   | email : required <br> code : required| check if code is vaild |
|/forgetPasswored/passwordResetProcess | post   | No   | email : required <br> code : required <br> password : rerquired <br> confirmPassword : required>| change password |


* ** verification end point**

| end point                 | Method | Auth |URL Params| Describe |
| :---:                     | :---:  | :---:|:---:     | :---:    |
| /verification/sendMail    | Post   | No   | email : required| send code for verfivarion |
| /verification             | Post   | No   | email : required <br> code : required| if code is correct Your email will be verfivarion|


* **profile end point**

| end point                 | Method | Auth |URL Params| Describe |
| :---:                     | :---:  | :---:|:---:     | :---:    |
| /profile                  | Post   | Yes  | _        | _        |
| /profile/details          | Post   | Yes  | _        | profile with details|
| /profile/edite            | Post   | Yes  |  email :  nullable <br /> fullName : nullable  <br /> phone : nullable <br /> gender : nullable <br /> birth : nullable <br />      | gender ==> (0->male, 1-> famale)|
| /profile/edite/image      | Post   | Yes  | iamge : required | _ |
| /changePassword           | Post   | Yes  | oldPassword : required <br> password : required <br> confirmPassword : required | _ |
| /profile/address/add      | Post   | Yes  | country : required <br> city : required <br> Neighborhood : required <br> region : required <br> street_name : required <br> building_number : required <br> notes : required | _ |
| /profile/address/edit     | Post   | Yes  | country : nullable <br> city : nullable <br> Neighborhood : nullable <br> region : nullable <br> street_name : nullable <br> building_number : nullable <br> notes : nullable | _ |


* ** cart end point**

| end point   | Method | Auth |URL Params| Describe |
| :---:       | :---:  | :---:|:---:     | :---:    |
| /cart       | get    | yes  | _        | get cart |
| /cart/add   | post   | yes  | product_id : required <br> quantity : required <br> color : nullable <br> size : nullable | _ |
| /cart/remove| post   | yes  | cart_id  | _ |
| /cart/edit  | post   | yes  | cart_id : required <br> quantity : required <br> color : nullable <br> size : nullable | _ |
| /cart/empty | post   | yes  | _ | _ |


* ** order end point**

| end point              | Method | Auth |URL Params| Describe |
| :---:                  | :---:  | :---:|:---:     | :---:    |
| /guest/promoCode/check | post   | No   | promo_code : required | _ |
| /order/cancel          | post   | Yes  | order_id : required   | _ |
| /order/address         | post   | Yes   | country : required <br> city : required <br> Neighborhood : required <br> region : required <br> street_name : required <br> building_number : required <br> notes : required | if user want another address to receive order (create a new addres and get id to use it in make order) |
| /order/make            | post   | Yes  | address_id : required <br> promo_code : nullable | _ |
| /order/details         | post   | yes  | order_id : required | _ |
| /order/tracking        | post   | yes  | status : required   | get all orders by orders status <br><br> status = 0 ->not active <br> status = 1-> Preparation and delivery <br> status = 2->finshed <br> status = 3->all |

* ** product end point**

| end point              | Method | Auth |URL Params| Describe |
| :---:                  | :---:  | :---:|:---:     | :---:    |
| /comment/add           | post   | Yes  | product_id : required <br> comment : required | _ |
| /comment/delete        | post   | Yes  | comment_id : required | _ |
| /comment/edit          | post   | Yes  | product_id : required <br> comment : required | _ |
| /love                  | post   | Yes  | product_id : required| if user add love this api remove it and if user don't add love this api add love |
| /loves                 | post   | Yes  | product_id : required <br> comment : required | get all products that user add love in it |
| /rating                | post   | Yes  | product_id : required <br> rating : required | rating -> from 1 to 5 |


* ** some page end point**

| end point       | Method | Auth |URL Params| Describe |
| :---:           | :---:  | :---:|:---:     | :---:    |
| /home           | get    | Yes  | _        | _ |
| /contact_us     | post   | Yes  | email : required <br> phone :required <br>  title : required <br>  body : required | _ |


<h1 align="center"> vendor apis </h1>

* ** Auth end point**

| end point        | Method | Auth |URL Params| Describe |
| :---:            | :---:  | :---:|:---:     | :---:    |
| /vender/login    | Post   | No   | email : required <br /> password : required| your acount should be verify |
| /vender/register | Post   | No   | email :  required <br /> fullName : required <br /> password : required <br /> confirm_password : required <br /> phone : required <br /> gender : required <br /> birth : required <br />  | you should verify your email <br> gender ==> (0->male, 1-> famale) |
| /vender/logout   | Post   | yes  | --       | --       |


* ** forget Passwored end point**

| end point                                   | Method | Auth |URL Params         | Describe |
| :---:                                       | :---:  | :---:|:---:              | :---:    |
| /vender/forgetPasswored/sendMail            | Post   | No   | email : required  | You will receive code in your email|
|/vender/forgetPasswored/checkCode            | post   | No   | email : required <br> code : required| check if code is vaild |
|/vender/forgetPasswored/passwordResetProcess | post   | No   | email : required <br> code : required <br> password : rerquired <br> confirmPassword : required>| change password |


* ** verification end point**

| end point                        | Method | Auth |URL Params| Describe |
| :---:                            | :---:  | :---:|:---:     | :---:    |
| /vender/verification/sendMail    | Post   | No   | email : required| send code for verfivarion |
| /vender/verification             | Post   | No   | email : required <br> code : required| if code is correct Your email will be verfivarion|


* **profile end point**

| end point                   | Method | Auth |URL Params| Describe |
| :---:                       | :---:  | :---:|:---:     | :---:    |
| /vender/profile             | Post   | Yes  | _        | _        |
| /vender/profile/edite       | Post   | Yes  |  email :  nullable <br /> fullName : nullable  <br /> phone : nullable <br /> gender : nullable <br /> birth : nullable <br /> | gender ==> (0->male, 1-> famale)|
| /vender/profile/edite/image | Post   | Yes  | iamge : required | _ |
| /changePassword             | Post   | Yes  | oldPassword : required <br> password : required <br> confirmPassword : required | _ |
| /profile/address/add        | Post   | Yes  | country : required <br> city : required <br> Neighborhood : required <br> region : required <br> street_name : required <br> building_number : required <br> notes : required | _ |
| /profile/address/edit       | Post   | Yes  | country : nullable <br> city : nullable <br> Neighborhood : nullable <br> region : nullable <br> street_name : nullable <br> building_number : nullable <br> notes : nullable | _ |


* ** product end point**

| end point                     | Method | Auth |URL Params| Describe |
| :---:                         |  :---: | :---:|  :---:   |  :---:   |
| /vender/products              | get    | Yes  |    _     |     _    |
| /vender/products/informations | get    | Yes  |    _     |     _    |
| /vender/products/money        | get    | Yes  |    _     |     _    |
| /vender/product/order         | get    | Yes  |    _     |     _    |
| /vender/product/add           | post   | Yes  | name : required <br> describe : required <br> price : required <br> quantity : required <br> gender : required <br> discound : required <br> sub_categoriesId : required <br> sizes[] : nullable <br> colors[] : nullable <br> iamges : required | _ |
| /vender/product/edit          | post   | Yes  | name : nullable <br> describe : nullable <br> price : nullable <br> quantity : nullable <br> gender : nullable <br> discound : nullable <br> sub_categoriesId : nullable <br> sizes[] : nullable <br> colors[] : nullable <br> iamges : nullable | _ |
| /ender/product/delete         | post   | Yes  |product_id : required | _ |


* ** some page end point**

| end point       | Method | Auth |URL Params| Describe |
| :---:           | :---:  | :---:|:---:     | :---:    |
| /home           | get    | Yes  | _        | _ |
| /contact_us     | post   | Yes  | email : required <br> phone :required <br>  title : required <br>  body : required | _ |


<h1 align="center"> guest apis </h1>

* ** end point**

| end point                     | Method | Auth |URL Params| Describe |
| :---:                         | :---:  | :---:|:---:     | :---:    |
| /guest/product/details        | get    | No   | product_id : required | _ |
| /guest/mainCategorys/details  | get    | No   | mainCategory_id : required | _ |

