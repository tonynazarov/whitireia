from src.sources.upwork.transformer import Transformer

if __name__ == '__main__':
    date = '20231202'
    data_dir = f'../data/{date}/upwork/upwork'
    transformer = Transformer(data_dir)
    transformer.scan_list_dir()