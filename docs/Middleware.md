## Middleware


### Operation
1. Only if the middleware is in the `staging` environment, it is active.
2. Check if the user is logged in.
    - if the user is not logged in, then go to login page.
    - if the user is logged in, and doesn't have got the role, go to dashboard page.
    - Otherwise, if user is logged in and has got the role, the user can go to every page. (QA team members and developers role is true, otherwise false)


### Management.
The middleware is affected by the `access_role` field in `users` table.
If a user wants to bypass the middleware, needs to set `access_role`'s value as true.