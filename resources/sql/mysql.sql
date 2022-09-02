-- #! mysql

-- # { economy
-- # { core
-- # { players
-- # { init
CREATE TABLE IF NOT EXISTS players
(
    xuid VARCHAR(16) PRIMARY KEY,
    name TEXT NOT NULL
);
-- # }

-- # { create
-- #    :xuid string
-- #    :name string
INSERT INTO players (xuid, name) VALUES (:xuid, :name);
-- # }

-- # { load
SELECT *
FROM players;
-- # }

-- # { update
-- #    :name string
-- #    :xuid string
UPDATE players
SET name = :name
WHERE xuid = :xuid;
-- # }

-- # { delete
-- #    :xuid string
DELETE
FROM players
WHERE xuid = :xuid;
-- # }

-- # { drop
DROP TABLE IF EXISTS players;
-- # }
-- # }

-- # { economys
-- # { init
CREATE TABLE IF NOT EXISTS economys
(
    xuid  VARCHAR(16) PRIMARY KEY,
    money INTEGER NOT NULL
);
-- # }

-- # { create
-- #    :xuid string
-- #    :money int
INSERT INTO economys (xuid, money) VALUES (:xuid, :money);
-- # }

-- # { load
SELECT *
FROM economys;
-- # }

-- # { update
-- #    :money int
-- #    :xuid string
UPDATE economys
SET money = :money
WHERE xuid = :xuid;
-- # }

-- # { delete
-- #    :xuid string
DELETE
FROM economys
WHERE xuid = :xuid;
-- # }

-- # { drop
DROP TABLE IF EXISTS economys;
-- # }
-- # }
-- # }
-- # }