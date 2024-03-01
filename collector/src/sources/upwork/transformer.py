import os
import json
import hashlib


class Transformer:

    def __init__(self, data_dir: str):
        self.data_dir = data_dir

    def scan_list_dir(self):
        list_dir = f'{self.data_dir}/list'
        for job_list in os.listdir(list_dir):
            file_path = f'{list_dir}/{job_list}'

            with open(file_path, 'r') as open_file:
                json_data = json.loads(open_file.read()).get('searchResults').get('jobs')

                for job in json_data:
                    self.sort_into_view(job)

    def sort_into_view(self, job):
        view_dir = f'{self.data_dir}/view'
        uid = hashlib.md5(job.get('uid').encode()).hexdigest()
        file_path = f'{view_dir}/{uid}.json'
        with open(file_path, 'x') as open_file:
            raw_json_data = json.dumps(job)

            open_file.write(raw_json_data)
