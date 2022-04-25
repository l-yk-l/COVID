import MySQLdb

def getTables(dbname):
	connect = MySQLdb.connect('localhost', 'root', '', dbname, charset = 'koi8r')
	cursor = connect.cursor()
	cursor.execute("SHOW TABLES FROM " + dbname)
	#cursor.execute("""SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_SCHEMA = """ + dbname)
	res = cursor.fetchall()
	cursor.close()
	connect.close()
	return res

def getAllData(dbname):
	tables = getTables(dbname)
	res = []
	connect = MySQLdb.connect('localhost', 'root', '', dbname, charset = 'koi8r')
	for t in tables:
		cursor = connect.cursor()
		cursor.execute("SELECT * FROM " + t[0])
		r = cursor.fetchall()
		res.append(r)
		cursor.close()
	connect.close()
	return res

def transactionTest(query):
	connect = MySQLdb.connect('localhost', 'sql_solver', 'sql_password', 'covid_unwrap', charset = 'koi8r')
	cursor = connect.cursor()

	root_conn = MySQLdb.connect('localhost', 'root', '', 'covid_unwrap', charset = 'koi8r')
	root_cur = root_conn.cursor()

	cursor.execute("SAVEPOINT SP")
	root_cur.execute("ALTER TABLE table1 AUTO_INCREMENT = 6")
	root_cur.execute("INSERT INTO table1 (name) VALUES ('Yura'), ('Vlad')")
	root_conn.commit()
	cursor.execute(query)
	cursor.execute("SELECT * FROM table1")
	arr = cursor.fetchall()
	print(arr)

	cursor.execute("ROLLBACK TO SP")
	root_cur.execute("ALTER TABLE table1 AUTO_INCREMENT = 6")
	cursor.execute(query)
	cursor.execute("SELECT * FROM table1")
	arr = cursor.fetchall()
	print(arr)

	cursor.close()
	connect.close()

def createDB(dbname):
	connect = MySQLdb.connect('localhost', 'root', '')
	cursor = connect.cursor()

	query = 'CREATE DATABASE ' + dbname + ' CHARACTER SET utf8 COLLATE utf8_unicode_ci'
	cursor.execute(query)

	cursor.close()
	connect.close()

def createTables(dbname, dump):
	connect = MySQLdb.connect('localhost', 'root', '', dbname, charset = 'koi8r')
	cursor = connect.cursor()

	for d in dump:
		cursor.execute(d)

	connect.commit()
	cursor.close()
	connect.close()

def dropDB(dbname):
	connect = MySQLdb.connect('localhost', 'root', '')
	cursor = connect.cursor()

	query = 'DROP DATABASE ' + dbname
	cursor.execute(query)

	cursor.close()
	connect.close()

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
def getDumps(id):
	connect = MySQLdb.connect('localhost', 'root', '', 'covid_test_db', charset = 'koi8r')
	cursor = connect.cursor()

	cursor.execute("""
		SELECT dump FROM dumps 
		INNER JOIN prob_dump ON dumps.id = prob_dump.dump_id 
		WHERE prob_dump.prob_id = """ + str(id))
	dump = cursor.fetchall()

	cursor.close()
	connect.close()
	return splitQuerys(dump)

# возвращает лист create и insert запросов в удобном для нас виде
def splitQuerys(query_list):
	res = []
	for l in query_list:
		sub = l[0].split(';')
		if sub[-1] == '':
			sub.pop()
		res.append(sub)
	return res

def executeQuery(dbname, query):
	connect = MySQLdb.connect('localhost', 'root', '', dbname, charset = 'koi8r')
	cursor = connect.cursor()

	cursor.execute(query)
	connect.commit()

	cursor.close()
	connect.close()

# получает результат выполнения запроса (все данные БД после проведения запроса)
def getRes(dbname, dump, query):
	createDB(dbname)
	createTables(dbname, dump)
	executeQuery(dbname, query)
	res = getAllData(dbname)
	dropDB(dbname)
	return res

def userQueryCheck(dbname, userQuery, rightQuery, problemID):
	dump = getDumps(problemID)
	qnt = len(dump)
	for i in range(qnt):
		user_res = getRes(dbname, dump[i], userQuery)
		right_res = getRes(dbname, dump[i], rightQuery)

		print()
		print("Test #" + str(i+1))
		print_row(user_res)
		print_row(right_res)

		if user_res != right_res:
			return i + 1 # фактический номер фейл-теста
	return 0 # всё пучком (AC)

def getDBName(user_id, problem_id):
	return 'covid_unwrap_' + str(user_id) + '_' + str(problem_id)

# эт мне для отладок разных надо было
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
	print("\n\nQuery #" + str(i) + ":")
	i += 1
	value = userQueryCheck(dbname, userQuery, rightQuery, problem_id)
	if not value:
		print("AC")
	else:
		print("WA" + str(value))

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