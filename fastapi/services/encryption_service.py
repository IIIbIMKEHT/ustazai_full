from cryptography.hazmat.primitives.ciphers import Cipher, algorithms, modes
from cryptography.hazmat.backends import default_backend
import base64
import binascii


def unpad_pkcs7(padded_data):
    padding_length = padded_data[-1]
    return padded_data[:-padding_length]

def add_base64_padding(encrypted_string):
    """Добавляем правильный padding для base64 строки."""
    padding_needed = 4 - (len(encrypted_string) % 4)
    if padding_needed:
        encrypted_string += "=" * padding_needed
    return encrypted_string

def decrypt_data(encrypted_string, key):
    # Восстанавливаем правильный padding для base64 строки
    encrypted_string = add_base64_padding(encrypted_string)
    
    # Декодируем base64
    encrypted_data = base64.urlsafe_b64decode(encrypted_string)
    
    # Извлекаем IV (первые 16 байт)
    iv = encrypted_data[:16]
    ciphertext = encrypted_data[16:]
    
    # Создаем объект шифра
    cipher = Cipher(algorithms.AES(key.encode()), modes.CBC(iv), backend=default_backend())
    decryptor = cipher.decryptor()
    
    # Расшифровываем данные
    padded_plaintext = decryptor.update(ciphertext) + decryptor.finalize()
    
    # Удаляем padding
    plaintext = unpad_pkcs7(padded_plaintext)
    
    return plaintext.decode('utf-8')