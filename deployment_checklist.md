# Production Deployment Checklist & Rollback Strategy

## Deployment Checklist
1. **Database Migrations**
   - [ ] Run `php artisan migrate --force` to create the `likes` table.
   - [ ] Ensure `user_id` and `likeable_id/type` indexes are created.

2. **Redis Configuration**
   - [ ] Verify Redis is installed and accessible in production.
   - [ ] Set `CACHE_STORE=redis` and `REDIS_CLIENT=phpredis` in `.env`.
   - [ ] Run `php artisan cache:clear` if necessary.

3. **Pusher & Broadcasting**
   - [ ] Set `BROADCAST_CONNECTION=pusher` in `.env`.
   - [ ] Add Pusher credentials (`PUSHER_APP_ID`, `PUSHER_APP_KEY`, etc.).
   - [ ] Ensure `npm run build` is executed for frontend assets (if using Echo).

5. **Static Analysis & Docs**
   - [ ] Run `vendor/bin/phpstan analyse --level 8` to ensure no regressions.
   - [ ] Run `php artisan l5-swagger:generate` to update API documentation.

## Rollback Strategy
1. **Code Rollback**
   - Revert the git commit using `git revert <commit-hash>`.
   - Redeploy the previous stable version.

2. **Database Rollback**
   - Run `php artisan migrate:rollback --step=1` to drop the `likes` table.
   - **Warning**: This will delete all user like data. Back up the `likes` table before rollback if data preservation is needed.

3. **Cache Cleanup**
   - Clear like-related cache keys: `redis-cli KEYS "likes_count:*" | xargs redis-cli DEL`.

4. **Frontend Rollback**
   - If the UI is broken, revert the Blade templates and rebuild assets.
