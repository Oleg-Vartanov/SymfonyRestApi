Get token
------------

### Request

```
POST http://domain/api/login_check
```

Params:

| Param    | Description  |
|----------|--------------|
| email    | Required     |
| password | Required     |

Body:
```
{
    "email": "testmail1@email.com",
    "password": "qwerty123"
}
```
### Response

**Success**

200
```
{
    "token": "{{token}}"
}
```