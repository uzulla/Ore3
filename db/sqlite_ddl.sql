PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE `session`
(
  id              TEXT NOT NULL PRIMARY KEY,
  user_account_id INTEGER NOT NULL,
  expire_at       INTEGER NOT NULL,
  csrf_token      INTEGER NOT NULL
);
CREATE TABLE `user_account`
(
  id              INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  email           TEXT NOT NULL,
  hashed_password TEXT NOT NULL,
  name            TEXT NOT NULL
);
INSERT INTO user_account VALUES(1,'user@example.jp','$2y$10$adAwOJdp7La6kwZ1ao5C.eDIfeuICneAbzHGz2cTK4PRZjMscYiPm','MR. pass is password');
COMMIT;