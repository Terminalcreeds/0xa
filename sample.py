import requests

test_file = 'imei.php'
data = {
    'api_key' : '1c2755023f354893ed17c99182d44464',
}

files = {'file' : open(test_file, 'rb')}
r = requests.post('http://www.unphp.net/api/v2/post', files=files, data=data)
file=open("Decrpted.php", "w")
file.write(r.text)
file.close()