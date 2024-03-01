import os
import json

def main():
    dir_path = os.path.dirname(os.path.realpath(__file__+'/../'))
    dir_data = os.path.dirname(dir_path+'/data/')

    summary = {}

    for subdir, dirs, files in os.walk(dir_data):
        for file in files:
            if file == 'result.json':
                filepath = os.path.join(subdir, file)

                segments = filepath.split('/')
                if summary.get(segments[6]) is None:
                    summary[segments[6]] = {}

                if summary[segments[6]].get(segments[7]) is None:
                    summary[segments[6]][segments[7]] = 0

                with open(filepath) as f:
                    data = json.load(f)
                    summary[segments[6]][segments[7]] +=int(data['jobs_total_ai_overall'])

    print(summary)

if __name__ == '__main__':
    main()
