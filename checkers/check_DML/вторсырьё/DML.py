from mysql.connector import connect

# Получает список таблиц БД по ее имени в виде листа кортежей:
"""
[('assignment',), ('categories',), ('group_user',), ('groups',),
('permission_role',), ('permissions',), ('problem',), ('problem_db',),
('problem_status',), ('roles',), ('testing_db',), ('theory',), ('users',)]
"""

def getTables(dbname):
	conn = connect(host='localhost', user='root',
			password='', database=dbname)
	cursor = conn.cursor()
	cursor.execute("SHOW TABLES FROM " + dbname)
	res = cursor.fetchall()
	cursor.close()
	conn.close()
	return res

# Получает все данные из всех таблиц указанной БД
def getAllData(dbname):
	tables = getTables(dbname)
	res = []
	conn = connect(host='localhost', user='root',
			password='', database=dbname)
	for t in tables:
		cursor = conn.cursor()
		cursor.execute("SELECT * FROM " + t[0])
		r = cursor.fetchall()
		res.append(r)
		cursor.close()
	conn.close()
	return res

# Создает БД для тестов
def createDB(dbname):
	conn = connect(host='localhost', user='root', password='')
	cursor = conn.cursor()

	query = 'CREATE DATABASE ' + dbname + ' CHARACTER SET utf8 COLLATE utf8_unicode_ci'
	cursor.execute(query)

	cursor.close()
	conn.close()

# Создзает таблицы в БД для тестов
def createTables(dbname, dump):
	conn = connect(host='localhost', user='root',
			password='', database=dbname)
	cursor = conn.cursor()

	for d in dump:
		cursor.execute(d)

	conn.commit()
	cursor.close()
	conn.close()

# Удаляет БД для тестов
def dropDB(dbname):
	conn = connect(host='localhost', user='root', password='')
	cursor = conn.cursor()

	query = 'DROP DATABASE ' + dbname
	cursor.execute(query)

	cursor.close()
	conn.close()

"""
#старый дроп таблиц в бд
def dropDB(drop):
	connect = MySQLdb.connect('localhost', 'root', '', 'covid_unwrap', charset = 'koi8r')
	cursor = connect.cursor()
	for d in drop:
		cursor.execute(d)
	connection.commit()
	cursor.close()
	connect.close()
"""

# Получает дампы тестов текущей задачи
def getDumps(id):
	conn = connect(host='localhost', user='root',
			password='', database='covid_test_db')
	cursor = conn.cursor()

	cursor.execute("""
		SELECT dump FROM dumps 
		INNER JOIN prob_dump ON dumps.id = prob_dump.dump_id 
		WHERE prob_dump.prob_id = """ + str(id))
	dump = cursor.fetchall()

	cursor.close()
	conn.close()
	return splitQuerys(dump)

# Возвращает лист create и insert запросов в удобном для нас виде
def splitQuerys(query_list):
	res = []
	for l in query_list:
		sub = l[0].split(';')
		if sub[-1] == '':
			sub.pop()
		res.append(sub)
	return res

"""
def executeQuery(dbname, query):
	conn = connect(host='localhost', user='dml_solver',
			password='dml_solve', database=dbname)
	cursor = conn.cursor()

	root_conn = connect(host='localhost', user='root',
			password='', database=dbname)
	root_cur = root_conn.cursor()

	table_name = "table1"


	root_cur.execute("SELECT `AUTO_INCREMENT`
						FROM INFORMATION_SCHEMA.TABLES
						WHERE TABLE_SCHEMA = '" + dbname + "' 
						AND TABLE_NAME = '" + table_name + "'")

	value = root_cur.fetchone()[0]
	
	root_cur.execute("SAVEPOINT SP")
	root_cur.execute("ALTER TABLE " + table_name + " AUTO_INCREMENT = " + str(value))
	#ALTER TABLE problems AUTO_INCREMENT = 1
	root_conn.commit()

	cursor.execute(query)
	arr = cursor.fetchall()
	print(arr)

	root_cur.execute("ROLLBACK TO SP")
	root_cur.execute("ALTER TABLE " + table_name + " AUTO_INCREMENT = " + str(value))
	cursor.execute(query)
	cursor.execute("SELECT * FROM table1")
	arr = cursor.fetchall()
	print(arr)

	cursor.close()
	connect.close()
"""

def executeQuery(dbname, query):
	conn = connect(host='localhost', user='dml_solver',
			password='dml_solve', database=dbname)
	cursor = conn.cursor()
	cursor.execute(query)
	conn.commit()
	cursor.close()
	conn.close()

# получает результат выполнения запроса (все данные БД после проведения запроса)
def getRes(dbname, dump, query):
	createDB(dbname)
	createTables(dbname, dump)
	executeQuery(dbname, query)
	res = getAllData(dbname)
	dropDB(dbname)
	return res

def printAllData(dbname, dump):
	createDB(dbname)
	createTables(dbname, dump)
	print("all data:", getAllData(dbname))
	dropDB(dbname)

def userQueryCheck(dbname, userQuery, rightQuery, problemID):
	dump = getDumps(problemID)
	qnt = len(dump)
	for i in range(qnt):
		user_res = getRes(dbname, dump[i], userQuery)
		right_res = getRes(dbname, dump[i], rightQuery)

		print()
		print("-=Test #" + str(i+1) + "=-")
		printAllData(dbname, dump[i])
		print()
		print("user_res:")
		#print_row(user_res)
		print(user_res)
		print("\nright_res:")
		#print_row(right_res)
		print(right_res)

		if user_res != right_res:
			return i + 1 # фактический номер фейл-теста
	return 0 # всё пучком (AC)

def getDBName(user_id, problem_id):
	return 'covid_unwrap_' + str(user_id) + '_' + str(problem_id)

# эт мне для отладак разных нада была
def print_row(row):
	for r in row:
		if r == '':
			print('-')
		else:
			print(r)

# --- user code --- #
"""
На вход поступают id юзера и задачи, из них составляется уникальное имя для БД
Также на вход поступают запрос юзера и эталонный запрос 
"""

"""Основа---
user_id = 1
problem_id = 1
dbname = getDBName(user_id, problem_id)

rightQuery = "INSERT INTO table1 (name) SELECT name FROM table2 WHERE id = 4"
q1 = "INSERT INTO table1 (name) SELECT name FROM table2 WHERE id = 1" # WA1
q2 = "INSERT INTO table1 (name) SELECT name FROM table2 WHERE id = 2" # WA2
q3 = "INSERT INTO table1 (name) SELECT name FROM table2 WHERE id = 3" # WA3
q4 = "INSERT INTO table1 (name) SELECT name FROM table2 WHERE id = 4" # AC
userQuerys = [q1, q2, q3, q4]

i = 1
for userQuery in userQuerys:
	print("\n\n---===Query #" + str(i) + ":===---")
	i += 1
	value = userQueryCheck(dbname, userQuery, rightQuery, problem_id)
	if not value:
		print("AC")
	else:
		print("WA" + str(value))
"""



"""
#INSERT INTO table1 (name) VALUES ('Yura'), ('Vlad'); 
query = "COMMIT"
transactionTest(query)
"""
# MyTests
"""
user_id = 1
problem_id = 1
dbname = getDBName(user_id, problem_id)

dump = getDumps(problem_id)
#createDB(dbname)
#createTables(dbname, dump[0])
dropDB(dbname)
"""

"""
user_id = 1
problem_id = 1
dbname = getDBName(user_id, problem_id)

tables = getTables(dbname)
res = []
connect = MySQLdb.connect('localhost', 'root', '', dbname, charset = 'koi8r')

cursor = connect.DictCursor()
cursor.execute("SELECT * FROM " + tables[0][0])
res = cursor.fetchall()
#res = cursor.fetchDict()
print(res)
"""