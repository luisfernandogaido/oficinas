SHOW GLOBAL STATUS LIKE 'Uptime';

SET @@global.event_scheduler = 0;

SELECT SCHEMA_NAME, sum_timer_wait/1000000000000 sum_timer_wait, last_seen, query_sample_text, digest_text
FROM `events_statements_summary_by_digest`
ORDER BY sum_timer_wait DESC;
-- ORDER BY last_seen DESC;

SELECT timer_wait/1000000000000 timer_wait, sql_text, current_schema, t.processlist_user, t.processlist_host
FROM `events_statements_current` e
INNER JOIN threads t ON e.thread_id = t.thread_id
ORDER BY timer_wait DESC;

SELECT * FROM INFORMATION_SCHEMA.PROCESSLIST ORDER BY TIME DESC;

SELECT * FROM threads;

KILL 10535;

select timer_wait/1000000000000 timer_wait, sql_text, current_schema
from `events_statements_history`
order by timer_wait desc;

select user, host, event_name, sum_timer_wait/1000000000000 sum_timer_wait
from `events_statements_summary_by_account_by_event_name`
order by sum_timer_wait desc;

select object_type, object_schema, object_name, sum_timer_wait/1000000000000 sum_timer_wait
from `events_statements_summary_by_program`
order by sum_timer_wait desc;

select user, host, event_name, sum_number_of_bytes_alloc/1024/1024/1024 sum_number_of_gb_alloc
from `memory_summary_by_account_by_event_name`
order by sum_number_of_bytes_alloc desc;

select object_type, object_schema, object_name, index_name, sum_timer_wait/1000000000000 sum_timer_wait
from `table_io_waits_summary_by_index_usage`
order by sum_timer_wait desc;

select object_type, object_schema, object_name, sum_timer_wait/1000000000000 sum_timer_wait
from `table_io_waits_summary_by_table`
order by sum_timer_wait desc;

select * from `users`;