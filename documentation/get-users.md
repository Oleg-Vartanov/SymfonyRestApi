Get users
------------

### Request

```
GET http://domain/api/users
```

Authorization header: `Bearer {{token}}`

### Response

**Success**

200

```
[
    {
        "id": 1,
        "first_name": "John",
        "last_name": "Woodcock",
        "email": "testmail1@email.com",
        "phone": "+486468464684"
    },
    {
        "id": 2,
        "first_name": "Jack",
        "last_name": "Marston",
        "email": "testmail2@email.com",
        "phone": "+486468464681"
    },
]
```

**Error**

401

```
{
    "code": 401,
    "message": "Invalid JWT Token"
}
```