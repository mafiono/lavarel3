cd ..
cd ..

mysqldump -u root -pibetup -d -B ibetupze --skip-comments | sed 's/ AUTO_INCREMENT=[0-9]*\b//' > "database/dump/ibetupze.sql"
mysqldump -u root -pibetup -d -B ibetupze2 --skip-comments | sed 's/ AUTO_INCREMENT=[0-9]*\b//' > "database/dump/ibetupze2.sql"

pause
