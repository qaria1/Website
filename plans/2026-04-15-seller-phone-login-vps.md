# Seller Phone Login VPS Notes

## Goal
- Make seller auth phone-first for the mobile app.
- Keep email optional on seller registration.
- Push the backend change to the VPS Git remote so the live API reflects the new contract.

## In Scope
- `app/Http/Controllers/RestAPI/v3/seller/auth/LoginController.php`
- `app/Http/Controllers/RestAPI/v3/seller/auth/RegisterController.php`
- Optional repo note for deployment flow.

## Out of Scope
- Database migrations.
- Frontend/mobile UI changes.
- Direct VPS filesystem changes outside Git deploy.

## API Contract Notes
- `POST /api/v3/seller/auth/login`
  - Required fields: `phone`, `password`
  - Phone may arrive in common Ethiopian formats such as `09xxxxxxxx`, `2519xxxxxxxx`, or `+2519xxxxxxxx`
  - Seller must still be `approved`
- `POST /api/v3/seller/registration`
  - `email` is optional
  - `phone` remains required

## Verification
- `git diff --check`
- `git status --short`
- `git push live main`

## Deployment
- The repository already points `live` at the VPS Git remote.
- Pushing `main` to `live` is the expected publish step for the live backend.
