Get user
------------

### Request

```
GET http://domain/api/users/{{id}}
```

Authorization header: `Bearer {{token}}`

### Response

**Success**

200

```
{
    "id": 1,
    "first_name": "John",
    "last_name": "Woodcock",
    "email": "testmail1@email.com",
    "phone": "+486468464684"
}
```

**Error**

401

```
{
    "code": 401,
    "message": "Invalid JWT Token"
}
```