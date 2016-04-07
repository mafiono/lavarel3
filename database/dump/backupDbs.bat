cd ..
cd ..

mysqldump -u root -pibetup -d -B ibetupze --skip-comments | sed 's/ AUTO_INCREMENT=[0-9]*\b//' > "database/dump/ibetupze.sql"
mysqldump -u root -pibetup -d -B ibetupze2 --skip-comments | sed 's/ AUTO_INCREMENT=[0-9]*\b//' > "database/dump/ibetupze2.sql"

mysqldump -h 160.153.16.53 -u ibetupze -pa6HTaTq.[0uI -d -B ibetupze --skip-comments | sed 's/ AUTO_INCREMENT=[0-9]*\b//' > database/dump/ibetupze_remote.sql
mysqldump -h 160.153.16.53 -u ibetupze -pa6HTaTq.[0uI -d -B ibetupco --skip-comments | sed 's/ AUTO_INCREMENT=[0-9]*\b//' > database/dump/ibetupco_remote.sql

mysqldump -u root -pibetup ibetupze > "database/dump/full_ibetupze.sql"
mysqldump -u root -pibetup ibetupco > "database/dump/full_ibetupco.sql"


mysqldump -h 160.153.16.53 -u ibetupze -pa6HTaTq.[0uI ibetupco > database/dump/full_ibetupco_remote.sql

mysql -u root -pibetup < betportugal.sql
mysql -u root -pibetup < create_all_fks.sql

chown -R ./tables | mysqldump --user=root -pibetup --no-data --tab=./tables ibetupco

pause
