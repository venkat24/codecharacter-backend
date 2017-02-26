import os
import sys
import signal
import zipfile
import subprocess
from time import sleep
from randomizer import add_random_ints

def run_simulator(scores_key, file_name, level_no):
    try:
        ld_export = "export LD_LIBRARY_PATH='/var/www/code-character/sim/code_character_simulator/codechar/lib';"

        # Move to the correct directory
        os.chdir('/var/www/code-character/sim/')

        # Clear the previously built simulator
        ret = subprocess.check_call("rm -rf /var/www/code-character/sim/code_character_simulator/", shell=True)
        if ret:
            print('Deletion of previously built simulator failed. Directory does not exist.')

        # Untar simulator
        sim_root_path = "code_character_simulator"
        clean_simulator_filename = "cc.tar"
        ret = subprocess.check_call("tar xvf {}".format(clean_simulator_filename), shell=True)
        os.chdir(sim_root_path)
        if ret:
            raise Exception('Untarring Failed')

        # Define install location of simulator
        install_dir = os.path.abspath("codechar")

        if not os.path.exists("codechar"):
            os.makedirs("codechar")
        if not os.path.exists("codechar/bin"):
            os.makedirs("codechar/bin")

        # Copy protobuf library
        ret = subprocess.check_call("cp -r ../protobuf/* ./codechar", shell=True)
        if ret:
            raise Exception('Copying Simulator Files failed')

        # Copy terrain files
        ret = subprocess.check_call("cp -r ../terrain/* ./codechar/bin", shell=True)
        if ret:
            raise Exception('Copying Simulator Files failed')

        # Insert random ints
        add_random_ints()

        # mkdir build_all && cd build_all
        build_location = "build_all"

        if not os.path.exists(build_location):
            os.makedirs(build_location)
        os.chdir(build_location)

        # Generate makefiles for simulator
        install_prefix = "-DCMAKE_INSTALL_PREFIX=" + install_dir
        project_prefix = "-DBUILD_ALL=ON"

        cmake_cmd = "{} cmake ../ {} {}".format(ld_export, install_prefix, project_prefix)
        ret = subprocess.check_call(cmake_cmd, shell=True)
        if ret:
            raise Exception('CMake simulator build failed')

        # make install the simulator
        ret = subprocess.check_call("{} make install".format(ld_export), shell=True)
        if ret:
            raise Exception('Simulator Make failed')

        # Move two levels up
        os.chdir("../..")

        # Untar player AI here
        zip_ref = zipfile.ZipFile(file_name, 'r')
        zip_ref.extractall(sim_root_path)
        zip_ref.close()

        # Move back into simulator root
        os.chdir(sim_root_path)

        # Copy untarred player AI to source for building
        copy_cmd = "cp -rn {} {}".format("./player1/include", "./src/player1/")
        ret = subprocess.check_call(copy_cmd, shell=True)
        if ret:
            raise Exception('Play AI copying failed')
        copy_cmd = "cp -r {} {}".format("./player1/src", "./src/player1/")
        ret = subprocess.check_call(copy_cmd, shell=True)
        if ret:
            raise Exception('Play AI copying failed')

        headers_to_remove=" \
        /var/www/code-character/sim/code_character_simulator/src/player1/include/actor/actor.fwd.h \
        /var/www/code-character/sim/code_character_simulator/src/player1/include/actor/actor.h \
        /var/www/code-character/sim/code_character_simulator/src/player1/include/player_state_handler/player_state_handler.h \
        /var/www/code-character/sim/code_character_simulator/src/player1/include/actor/base.h \
        /var/www/code-character/sim/code_character_simulator/src/player1/include/actor/fire_ball.h \
        /var/www/code-character/sim/code_character_simulator/src/player1/include/actor/flag.fwd.h \
        /var/www/code-character/sim/code_character_simulator/src/player1/include/actor/flag.h \
        /var/www/code-character/sim/code_character_simulator/src/player1/include/actor/king.fwd.h \
        /var/www/code-character/sim/code_character_simulator/src/player1/include/actor/king.h \
        /var/www/code-character/sim/code_character_simulator/src/player1/include/actor/magician.h \
        /var/www/code-character/sim/code_character_simulator/src/player1/include/actor/projectile_handler.h \
        /var/www/code-character/sim/code_character_simulator/src/player1/include/actor/scout.h \
        /var/www/code-character/sim/code_character_simulator/src/player1/include/actor/states/actor_attack_state.h \
        /var/www/code-character/sim/code_character_simulator/src/player1/include/actor/states/actor_dead_state.h \
        /var/www/code-character/sim/code_character_simulator/src/player1/include/actor/states/actor_idle_state.h \
        /var/www/code-character/sim/code_character_simulator/src/player1/include/actor/states/actor_path_planning_state.h \
        /var/www/code-character/sim/code_character_simulator/src/player1/include/actor/states/actor_state.h \
        /var/www/code-character/sim/code_character_simulator/src/player1/include/actor/swordsman.h \
        /var/www/code-character/sim/code_character_simulator/src/player1/include/actor/tower.h \
        /var/www/code-character/sim/code_character_simulator/src/player1/include/path_planner/formation.h \
        /var/www/code-character/sim/code_character_simulator/src/player1/include/path_planner/graph.h \
        /var/www/code-character/sim/code_character_simulator/src/player1/include/path_planner/path_planner.h \
        /var/www/code-character/sim/code_character_simulator/src/player1/include/path_planner/path_planner_helper.h \
        /var/www/code-character/sim/code_character_simulator/src/player1/include/player_state_handler/unit_views.h \
        /var/www/code-character/sim/code_character_simulator/src/player1/include/state.h \
        /var/www/code-character/sim/code_character_simulator/src/player1/include/terrain/terrain.h \
        /var/www/code-character/sim/code_character_simulator/src/player1/include/terrain/terrain_element.h \
        /var/www/code-character/sim/code_character_simulator/src/player1/include/utilities.h \
        /var/www/code-character/sim/code_character_simulator/src/player1/include/vector2d.h \
        /var/www/code-character/sim/code_character_simulator/src/player1/include/ipc.h \
        /var/www/code-character/sim/code_character_simulator/src/player1/include/player_ai.h \
        /var/www/code-character/sim/code_character_simulator/src/player1/include/player_ai_helper.h \
        /var/www/code-character/sim/code_character_simulator/src/player1/include/ipc_export.h \
        /var/www/code-character/sim/code_character_simulator/src/player1/include/physics_export.h \
        /var/www/code-character/sim/code_character_simulator/src/player1/include/player_export.h \
        /var/www/code-character/sim/code_character_simulator/src/player1/include/state_export.h \
        "
        ret = subprocess.run("rm {}".format(headers_to_remove), shell=True) 
        if ret:
            print('Removal was not successful')

        # mkdir build_player && cd build_player
        build_location = "build_player"

        if not os.path.exists(build_location):
            os.makedirs(build_location)
        os.chdir(build_location)

        # Generate makefiles for player AI
        project_prefix = "-DBUILD_PLAYER1=ON"
        cmake_cmd = "{} cmake ../ {} {}".format(ld_export, install_prefix, project_prefix)
        ret = subprocess.check_call(cmake_cmd, shell=True)
        if ret:
            raise Exception('Player AI CMake build falied')

        # make install the player AI
        ret = subprocess.check_call("{} make install".format(ld_export), shell=True)
        if ret:
            Exception('Player AI build failed')

        # Move one level up
        os.chdir("..")

        # cd to simulator executable location
        os.chdir(os.path.join(install_dir, "bin"))

        # Run the simulator!!!
        terrain_file_loc = "level01_terrain"

        os.environ["LD_LIBRARY_PATH"] = "/var/www/code-character/sim/code_character_simulator/codechar/lib/"
        sim_exec_cmd = "./main h {} {} {}".format(level_no, terrain_file_loc, scores_key)
        sim_exec_cmd = "LD_LIBRARY_PATH={} {}".format(os.path.join(install_dir, "lib"), sim_exec_cmd)

        sim_proc = subprocess.Popen(sim_exec_cmd, shell=True, stdout=subprocess.PIPE, preexec_fn=os.setsid)

        # Keep checking if simulator is done
        time_running = 0
        max_time = 5 * 60
        while (True):
            print("{} ... ".format(time_running),)
            sleep(1)

            if sim_proc.poll() is not None:
                break

            time_running = time_running + 1

            if time_running > max_time + 30:
                os.killpg(os.getpgid(sim_proc.pid), signal.SIGTERM)  # Send the signal to all the process groups


        sim_raw_output = sim_proc.stdout.read().decode('utf-8')
        print(sim_raw_output)
        sim_output_parts = sim_raw_output.split(':')
        print(sim_output_parts)
        check_string = sim_output_parts[-3][-50:]
        print(check_string)
        print(scores_key)
        if check_string != scores_key:
            print('INVALID SCHECK STRING')
            raise Exception('Invalid check string was obtained.')
        final_score = (int(sim_output_parts[-2]),int(sim_output_parts[-1][:-1]))
        print(final_score)

        # Change directory to one above simulator root
        os.chdir("../../../")

        subprocess.check_call("rm -rf {}".format(sim_root_path), shell=True)

        return final_score
    except Exception as e:
        print(e)
        return (-999,-999)
