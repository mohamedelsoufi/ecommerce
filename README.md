**Show User**
----
* **URL**
    http://localhost/ahmedmaher/laravel/projects/ecommerce/api/

* **Success Response:**

  * **status:** true <br />
    **message:** message <br />
    **data:** `{ id : 12, .. }` <br />
 
* **Error Response:**

  * **successful:** false <br />
    **status:** "E00" <br />
    **message:** message <br />

* ** Auth end point**

| end point | Method | Auth |URL Params| Describe |
| :---:     | :---:  | :---:|:---:     | :---:    |
| /login    | Post   | No   | email : required <br /> password : required| your acount should be verify |
| /register | Post   | No   | email :  required <br /> fullName : required <br /> password : required <br /> confirm_password : required <br /> phone : required <br /> gender : required <br /> birth : required <br />  | you should verify your email |
| /logout   | Post   | yes  | --       | --       |
| /logout   | Post   | yes  | --       | --       |

 
