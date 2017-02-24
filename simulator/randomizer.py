import os
import fileinput
from random import randint

def add_random_ints():
    try:
        no_ints = randint(256, 4096)
        psh_file_path = "src/state/include/player_state_handler/player_state_handler.h"
        ints = ""
        for i in range(no_ints):
            ints = ints + "\tint a" + str(i) + "=0;\r\n"

        for line in fileinput.input(os.path.abspath(psh_file_path), inplace=1):
            if "State * state;" in line:
                print(ints + line),
            else:
                print(line),
    except Exception as e:
        print('Problem in RANDOMIZER')
        print(e)

if __name__ == "__main__":
	add_random_ints()
