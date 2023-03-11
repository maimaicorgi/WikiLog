CREATE TABLE IF NOT EXISTS logs
(
    domain_code         VARCHAR(10)  NOT NULL,
    page_title          VARCHAR(100) NOT NULL,
    count_views         INTEGER      NOT NULL,
    total_response_size INTEGER      NOT NULL
);
