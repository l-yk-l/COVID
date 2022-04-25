import MySQLdb

def createDB(dbname):
	connect = MySQLdb.connect('localhost', 'root', '')
	cursor = connect.cursor()

	query = 'CREATE DATABASE ' + dbname + ' CHARACTER SET utf8 COLLATE utf8_unicode_ci'
	cursor.execute(query)

	cursor.close()
	connect.close()

# на вход приходят имя бд и дампа одного теста
def createTables(dbname, dump):
	connect = MySQLdb.connect('localhost', 'root', '', dbname, charset = 'koi8r')
	cursor = connect.cursor()
	
	for d in dump:
		cursor.execute(d)
	connect.commit()

	cursor.close()
	connect.close()

def dropTables(dbname):
	tables = getTables(dbname)
	connect = MySQLdb.connect('localhost', 'root', '', dbname, charset = 'koi8r')
	cursor = connect.cursor()
	cursor.execute("DROP TABLE " + tables)
	cursor.close()
	connect.close()

def getTables(dbname):
	connect = MySQLdb.connect('localhost', 'root', '', dbname, charset = 'koi8r')
	cursor = connect.cursor()
	cursor.execute("SHOW TABLES FROM " + dbname)
	res = cursor.fetchall()
	cursor.close()
	connect.close()
	tables = ""
	for r in res:
		tables += str(r[0]) + ", "
	tables = tables[:-2]
	return tables

def dropDB(dbname):
	connect = MySQLdb.connect('localhost', 'root', '')
	cursor = connect.cursor()

	query = 'DROP DATABASE ' + dbname
	cursor.execute(query)

	cursor.close()
	connect.close()

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

# если задача подразумевает сортировку в своем условии - needSort = False
def userQueryCheck(problemID, userQuery, rightQuery, dbname, needSort):
	createDB(dbname)
	print("db created")
	dumps = getDumps(problemID)
	
	if needSort:
		if userQuery[-1] == ';':
			userQuery = userQuery[:-1]
		userQuery += " ORDER BY id" # "id" - это пока заглушка, на его месте может быть наш искуственный PK

	connect = MySQLdb.connect('localhost', 'sql_solver', 'sql_solve', dbname, charset = 'koi8r')
	cursor = connect.cursor()
	print("connected!")
	print("user query: " + userQuery)
	print("right query: " + rightQuery)

	for i in range(len(dumps)):
		print("dump #" + str(i + 1) +': ')
		print(dumps[i])
		createTables(dbname, dumps[i])
		print("Tables created for test #" + str(i+1))
		user_res = ""
		try:
			cursor.execute(userQuery)
			user_res = cursor.fetchall()
		except Exception as e:
			return "Error, " + format(e)

		cursor.execute(rightQuery)
		right_res = cursor.fetchall()
		print("user res: ")
		print(user_res)
		print("right_res: ")
		print(right_res)
		connect.commit()
		dropTables(dbname)
		print("tables droped")

		if user_res != right_res:
			print("UNSOLVED")
			dropDB(dbname)
			cursor.close()
			connect.close()
			return i + 1 # номер теста, на котором произошел WA
		else:
			print("SOLVED")
	dropDB(dbname)
	cursor.close()
	connect.close()
	return 0 # AC


def getDBname(userID, problemID):
	return "covid_unwrap_" + str(userID) + "_" + str(problemID)


userID = 1
problemID = 1
dbname = getDBname(userID, problemID)

userQuery = "SELECT name, id FROM table1 WHERE id > 2"
rightQuery = "SELECT name, id FROM table1 WHERE id > 2 ORDER BY id"

res = userQueryCheck(problemID, userQuery, rightQuery, dbname, True)
print(res)


#createDB(dbname)
#dumps = getDumps(1)
#createTables(dbname, dumps[0])
#print(getTables(dbname))
#dropTables(dbname)
#dropDB(dbname)