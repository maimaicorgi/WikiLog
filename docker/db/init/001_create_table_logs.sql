CREATE TABLE IF NOT EXISTS logs
(
    domain_code         VARCHAR(20)  NOT NULL,
    page_title          VARCHAR(300) NOT NULL,
    count_views         INTEGER      NOT NULL,
    total_response_size INTEGER      NOT NULL
);
