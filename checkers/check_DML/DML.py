from mysql.connector import connect
import sys

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
	conn = conn = connect(host='localhost', user='root',
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

# Получает дампы тестов текущей задачи
def getDumps(id):
	conn = connect(host='localhost', user='root',
			password='', database='covid')
	cursor = conn.cursor()

	cursor.execute("""
		SELECT dump FROM dml_differences 
		INNER JOIN dml_problem_diff ON dml_differences.id = dml_problem_diff.diff_id 
		WHERE dml_problem_diff.prob_id = """ + str(id))
	dump = cursor.fetchall()

	cursor.close()
	conn.close()
	return splitQuerys(dump)

def getDiffFromDB(id):
	conn = connect(host='localhost', user='root',
			password='', database='covid')
	cursor = conn.cursor()

	cursor.execute("""
		SELECT a_diff_b, b_diff_a FROM dml_differences 
		INNER JOIN dml_problem_diff ON dml_differences.id = dml_problem_diff.diff_id 
		WHERE dml_problem_diff.prob_id = """ + str(id))
	diffs = cursor.fetchall()

	cursor.close()
	conn.close()

	return diffs

# Возвращает лист create и insert запросов в удобном для нас виде
def splitQuerys(query_list):
	res = []
	for l in query_list:
		sub = l[0].split(';')
		if sub[-1] == '':
			sub.pop()
		res.append(sub)
	return res

def executeQuery(dbname, query):
	conn = connect(host='localhost', user='dml_solver',
			password='dml_solve', database=dbname)
	cursor = conn.cursor()
	cursor.execute(query)
	conn.commit()
	cursor.close()
	conn.close()

# получает результат выполнения запроса (все данные БД после проведения запроса)
def getRes(dbname, query):
	executeQuery(dbname, query)
	res = getAllData(dbname)
	return res

def printAllData(dbname, dump):
	createDB(dbname)
	createTables(dbname, dump)
	#print("all data:", getAllData(dbname))
	dropDB(dbname)

def userQueryCheck(dbname, userQuery, problemID):
	dump = getDumps(problemID)
	diffs = getDiffFromDB(problem_id)
	qnt = len(dump)
	for i in range(qnt):
		a_b = diffs[i][0]
		b_a = diffs[i][1]
		createDB(dbname)
		createTables(dbname, dump[i])

		a = getAllData(dbname)
		b = getRes(dbname, userQuery)

		dropDB(dbname)
		"""
		print()
		print("-=Test #" + str(i+1) + "=-")
		#printAllData(dbname, dump[i])
		print()
		print("a_diff_b:")
		print(getDifference(a, b))
		print("\nb_diff_a:")
		print(getDifference(b, a))
		print("\na_b:")
		print(a_b)
		print("\nb_a:")
		print(b_a)
		"""
		if a_b != getDifference(a, b) or b_a != getDifference(b, a):
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

# Возвращает a\b
def getDifference(a, b):
	n = len(a)
	res = []
	for i in range(n):
		r = []
		for j in range(len(a[i])):
			f = True
			for k in range(len(b[i])):
				#print(a[i][j])
				#print(b[i][k])
				#print('=========')
				if(a[i][j] == b[i][k]):
					f = False
			if(f):
				r.append(a[i][j])
			#print()
		res.append(r)
	return str(res)

# --- user code --- #
"""
На вход поступают id юзера и задачи, из них составляется уникальное имя для БД
Также на вход поступают запрос юзера и эталонный запрос
"""

"""Основа---"""

user_query = sys.argv[1]
problem_id = int(sys.argv[2])
user_id = int(sys.argv[3])

dbname = getDBName(user_id, problem_id)

#q1 = "INSERT INTO table1 (name) SELECT name FROM table2 WHERE id = 1" # WA1
#q2 = "INSERT INTO table1 (name) SELECT name FROM table2 WHERE id = 2" # WA2
#q3 = "INSERT INTO table1 (name) SELECT name FROM table2 WHERE id = 3" # WA3
#q4 = "INSERT INTO table1 (name) SELECT name FROM table2 WHERE id = 4" # AC

try:
	val = userQueryCheck(dbname, user_query, problem_id)
except Exception as e:
	dropDB(dbname)
	print(str(e))

print(val)