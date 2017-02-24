import os
import subprocess
import sys
import zipfile
from time import sleep
from randomizer import add_random_ints

def run_simulator(scores_key, file_name, level_no):

    ld_export = "export LD_LIBRARY_PATH='/var/www/code-character/sim/code_character_simulator/codechar/lib';"
    # Untar simulator here
    clean_simulator_filename = "cc.tar"
    subprocess.check_call("tar xvf {}".format(clean_simulator_filename), shell=True)
    sim_root_path = "code_character_simulator"
    os.chdir(sim_root_path)

    # Define install location of simulator
    install_dir = os.path.abspath("codechar")

    if not os.path.exists("codechar"):
        os.makedirs("codechar")
    if not os.path.exists("codechar/bin"):
        os.makedirs("codechar/bin")

    # Copy protobuf library
    subprocess.check_call("cp -r ../protobuf/* ./codechar", shell=True)

    # Copy terrain files
    subprocess.check_call("cp -r ../terrain/* ./codechar/bin", shell=True)

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
    subprocess.check_call(cmake_cmd, shell=True)

    # make install the simulator
    subprocess.check_call("{} make -j install".format(ld_export), shell=True)

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
    subprocess.check_call(copy_cmd, shell=True)
    copy_cmd = "cp -r {} {}".format("./player1/src", "./src/player1/")
    subprocess.check_call(copy_cmd, shell=True)

    # mkdir build_player && cd build_player
    build_location = "build_player"

    if not os.path.exists(build_location):
        os.makedirs(build_location)
    os.chdir(build_location)

    # Generate makefiles for player AI
    project_prefix = "-DBUILD_PLAYER1=ON"
    cmake_cmd = "{} cmake ../ {} {}".format(ld_export, install_prefix, project_prefix)
    subprocess.check_call(cmake_cmd, shell=True)

    # make install the player AI
    subprocess.check_call("{} make -j install".format(ld_export), shell=True)

    # Move one level up
    os.chdir("..")

    # cd to simulator executable location
    os.chdir(os.path.join(install_dir, "bin"))

    # Run the simulator!!!
    terrain_file_loc = "level01_terrain"

    os.environ["LD_LIBRARY_PATH"] = "/var/www/code-character/sim/code_character_simulator/codechar/lib/"
    sim_exec_cmd = "./main h {} {} {}".format(level_no, terrain_file_loc, scores_key)
    sim_exec_cmd = "LD_LIBRARY_PATH={} {}".format(os.path.join(install_dir, "lib"), sim_exec_cmd)

    sim_proc = subprocess.Popen(sim_exec_cmd, shell=True, stdout=subprocess.PIPE)

    # Keep checking if simulator is done
    time_running = 0
    max_time = 5 * 60
    while (True):
        print("{} ... ".format(time_running),)
        sleep(1)

        if sim_proc.poll() is not None:
            break

        time_running = time_running + 1

        if time_running > max_time + 15:
            sim_proc.kill()

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
