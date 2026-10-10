from rembg import remove, new_session
from PIL import Image

input_path = 'public/images/logo.jpg'
output_path = 'public/images/logo.png'

print("Opening image...")
input = Image.open(input_path)
print("Removing background with u2netp...")
session = new_session('u2netp')
output = remove(input, session=session)
print("Saving image...")
output.save(output_path)
print("Done!")
