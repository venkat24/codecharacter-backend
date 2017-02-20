import sqlalchemy
import os, sys, time, shutil
import pymysql.cursors

engine = sqlalchemy.create_engine('mysql+pymysql://root:pwd@localhost/code_character')

def runSubmissions():
    # This function shouldn't take any args. It should read from the database queue
    while True:
        queue = engine.execute('SELECT * FROM `queue`')
        queueList = []
        for row in queue:
            queueList.append(dict(zip(row.keys(), row)))
        if(queueList == []):
            print('No New Jobs')
        for i in range(len(queueList)):
            queueId = queueList[i]['id']
            getSubmissionQuery="""
            SELECT `submittedCode` from `submissions` WHERE `id` = {0}
            """
            submission = engine.execute(getSubmissionQuery.format(queueId))
            with open('code_{0}.zip'.format(queueList[i]['id']),'wb') as f:
                for row in submission:
                    f.write(row[0]) # Write the .zip file
                    # Write simulator logic here
                    print('Running Simulation ... ')
                    for i in range(5):
                        print('...')
                        time.sleep(0.5)
                    # Once the simulation is over, kill the row in the queue
                    deleteFromQueueQuery = """
                    DELETE FROM `queue` WHERE `id` = {0}
                    """
                    engine.execute(deleteFromQueueQuery.format(queueId))
                    print('Deleted job from queue')
                    # Read the final score here and update it in the submissions table
        time.sleep(5)

if __name__=='__main__':
    runSubmissions()
