/*var rfc = "ETI120511BF1";
var base = $("base").attr("href");
if(base === "/cbiz-dev/admin/")
{
    var newCsd =
    {
        "Rfc": "URE180429TM6",
        "Certificate": "MzA4MiAwNThhIDMwODIgMDM3MiBhMDAzIDAyMDEgMDIwMiAxNDMzCjMwMzAgMzAzMSAzMDMwIDMwMzAgMzAzMCAzNDMwIDMwMzAgMzAzMgozMzMzIDMwMzAgMGQwNiAwOTJhIDg2NDggODZmNyAwZDAxIDAxMGIKMDUwMCAzMDgyIDAxMmIgMzEwZiAzMDBkIDA2MDMgNTUwNCAwMzBjCjA2NDEgNDMyMCA1NTQxIDU0MzEgMmUzMCAyYzA2IDAzNTUgMDQwYQowYzI1IDUzNDUgNTI1NiA0OTQzIDQ5NGYgMjA0NCA0NTIwIDQxNDQKNGQ0OSA0ZTQ5IDUzNTQgNTI0MSA0MzQ5IDRmNGUgMjA1NCA1MjQ5CjQyNTUgNTQ0MSA1MjQ5IDQxMzEgMWEzMCAxODA2IDAzNTUgMDQwYgowYzExIDUzNDEgNTQyZCA0OTQ1IDUzMjAgNDE3NSA3NDY4IDZmNzIKNjk3NCA3OTMxIDI4MzAgMjYwNiAwOTJhIDg2NDggODZmNyAwZDAxCjA5MDEgMTYxOSA2ZjczIDYzNjEgNzIyZSA2ZDYxIDcyNzQgNjk2ZQo2NTdhIDQwNzMgNjE3NCAyZTY3IDZmNjIgMmU2ZCA3ODMxIDFkMzAKMWIwNiAwMzU1IDA0MDkgMGMxNCAzMzcyIDYxMjAgNjM2NSA3MjcyCjYxNjQgNjEyMCA2NDY1IDIwNjMgNjE2NCA2OTdhIDMxMGUgMzAwYwowNjAzIDU1MDQgMTEwYyAwNTMwIDM2MzMgMzczMCAzMTBiIDMwMDkKMDYwMyA1NTA0IDA2MTMgMDI0ZCA1ODMxIDE5MzAgMTcwNiAwMzU1CjA0MDggMGMxMCA0MzQ5IDU1NDQgNDE0NCAyMDQ0IDQ1MjAgNGQ0NQo1ODQ5IDQzNGYgMzExMSAzMDBmIDA2MDMgNTUwNCAwNzBjIDA4NDMKNGY1OSA0ZjQxIDQzNDEgNGUzMSAxMTMwIDBmMDYgMDM1NSAwNDJkCjEzMDggMzIyZSAzNTJlIDM0MmUgMzQzNSAzMTI1IDMwMjMgMDYwOQoyYTg2IDQ4ODYgZjcwZCAwMTA5IDAyMTMgMTY3MiA2NTczIDcwNmYKNmU3MyA2MTYyIDZjNjUgM2EyMCA0MTQzIDQ0NGQgNDEyZCA1MzQxCjU0MzAgMWUxNyAwZDMxIDM5MzAgMzUzMiAzOTMxIDM4MzMgMzczNAozMjVhIDE3MGQgMzIzMyAzMDM1IDMyMzkgMzEzOCAzMzM3IDM0MzIKNWEzMCA4MWIxIDMxMWQgMzAxYiAwNjAzIDU1MDQgMDMxMyAxNDQ5CjRlNDcgNTI0OSA0NDIwIDU4NGYgNDQ0MSA1MjIwIDRhNDkgNGQ0NQo0ZTQ1IDVhMzEgMWQzMCAxYjA2IDAzNTUgMDQyOSAxMzE0IDQ5NGUKNDc1MiA0OTQ0IDIwNTggNGY0NCA0MTUyIDIwNGEgNDk0ZCA0NTRlCjQ1NWEgMzExZCAzMDFiIDA2MDMgNTUwNCAwYTEzIDE0NDkgNGU0Nwo1MjQ5IDQ0MjAgNTg0ZiA0NDQxIDUyMjAgNGE0OSA0ZDQ1IDRlNDUKNWEzMSAxNjMwIDE0MDYgMDM1NSAwNDJkIDEzMGQgNTg0ZiA0YTQ5CjM3MzQgMzAzOSAzMTM5IDU1MzQgMzgzMSAxYjMwIDE5MDYgMDM1NQowNDA1IDEzMTIgNTg0ZiA0YTQ5IDM3MzQgMzAzOSAzMTM5IDRkNTEKNTQ0NCA0ZDRlIDMwMzIgMzExZCAzMDFiIDA2MDMgNTUwNCAwYjEzCjE0NDkgNGU0NCA1MjQ5IDQ0MjAgNTg0ZiA0NDQxIDUyMjAgNGE0OQo0ZDQ1IDRlNDUgNWEzMCA4MjAxIDIyMzAgMGQwNiAwOTJhIDg2NDgKODZmNyAwZDAxIDAxMDEgMDUwMCAwMzgyIDAxMGYgMDAzMCA4MjAxCjBhMDIgODIwMSAwMTAwIDhhM2MgYmU4NCA0MDA0IDgwN2EgNmFhMwo3ZGE1IDRkZTUgMjU2NCA4ZmEzIDQ5OGMgYzdmYSBjMmU2IDU1MmUKMzdlYSBiMDRlIGVjMDMgN2VhZCA3OWVhIGRhM2QgNzg5ZSA2ZDQwCjhjYmUgM2I5NiAxODBlIDhmMWIgNzE5MSA2YzM0IGQ5YjYgZDFjZgpmMTM3IGQ1YjkgOGRjNCBjMjM4IDQ0MzAgZjAyZiA1NWI3IDc2MDEKYzZmNSBiOTMyIDdlNjEgZmFmZSAwMGMyIGY3OWUgMzc2NSAxMzE4CmZmZTEgNGNhYyA3YjJhIDFkNTEgYjllNSAwYmM3IGIyZjMgMjYwNgozY2QwIDIyNDcgMzhkNCA0ZmNhIDY4ZGYgMzYzZiA4NGZhIGRlZmIKNjYxOCBiMjNiIDYyNDcgNDk0ZiBkOGI2IDgyYjMgOWIyZSBiZGZkCjRjYzAgYTFiNCA0OWRlIGUwODggZDhlNCA3OWJhIDU4MDggYmQzMAoxODk0IDJhMzUgMjU2NyAxNmNkIDFiZjMgMmQxMiBlNzE1IDJmNjMKYThjOCAzMzMxIGNkZjMgZjBiMSAxMjc1IGRhZDYgMjQ3ZiAwMmZjCjI2NjUgNGE0NSAyZTU2IGY2NTcgZDEwYyAxYTM2IDgwYWIgZDAxOQowMTBiIDU5ZTIgNDliYiA2YjMwIGI1MWYgOWVhOSA5NDE3IDNlNDMKOTUzMiAyNTkzIGRjNWQgMWU0NSA5OGYxIGYyNjEgYWMzMSA3YTIwCjc0NGMgYjI2NCBhYjE0IDEwYzYgODVlOCAwY2JmIGE1MzAgZmM0ZQplMzlmIDMyYmIgMjU3MyAwMjAzIDAxMDAgMDFhMyAxZDMwIDFiMzAKMGMwNiAwMzU1IDFkMTMgMDEwMSBmZjA0IDAyMzAgMDAzMCAwYjA2CjAzNTUgMWQwZiAwNDA0IDAzMDIgMDZjMCAzMDBkIDA2MDkgMmE4Ngo0ODg2IGY3MGQgMDEwMSAwYjA1IDAwMDMgODIwMiAwMTAwIDMzYzUKNDY1ZiBkNTRiIGZmZmEgYWZmZiA4MTJkIGY4NWIgMjU4MCAxYzllCjFlMzggZjEwOCA4ZTllIGViYzQgNWM2MyA2Nzg2IDU2YWMgZDc4Zgo5ZDU2IDdkMDIgNTEyNCA5MzdjIDkwNTkgOThhMSBmYWJjIGViNWUKYWJkNyAwNDhkIDViYzYgYThmMCA0Y2ZjIDljNjIgNmJiZCBiMTQzCjQ5ZTMgMTQwNiBhZjYxIGExYTIgYjk1NSAwMGVjIDM1NWQgY2U1OApjYjgyIDg1NWMgMmEwNiAzZWQyIDE3NmYgMTZkNSA3NjkyIDQ2M2MKZDk2NyAxODJlIGVjZGIgNzYwYiAzOWMyIDkwMjMgY2FkNSA1ODU5CmVkN2EgNmVhMyA2YjJjIDdkMTcgMDE1ZSBjNzg3IGRkYjQgZGVmZQo4NTU3IGRkYmYgMzg1MSBiOGZlIGRmMjIgNjg2ZiA5NjljIGNmZjQKNzIzYyBkODI1IGExNGIgZTlmZiBhZTkyIGEyZjEgOTgyYyAwNGQ3CjQ3ZmEgZmY5OSA0YWE3IGI5MDQgNjI1ZSBlMDRjIDNlNzAgMjcwZgoyMTFlIGY3YTUgYWEwMiBmZWExIDk3NDEgOTI1MSBiY2UwIDY5M2IKNmJkYiBkNDIzIGYzMmMgZjk1MCBjZTBjIDBiMzggNTI2MCAwYWZjCmFjOWIgNjNjNCAzN2MxIDllODQgOTQ1ZSA0MTQzIGU5OTUgYWIxYgo2OGMwIDI5MmIgMmVmMiBkZWVjIGQxMDIgMjBmYyAzMmE2IGE4YTIKNWQ1YSBhN2YyIDNmNjcgNWIzYiBhZDE4IGU0MTIgYmViYyA5MmY1CmM1NzggMjNhNyAzYWYyIDkxZTQgYWY0OCBhNTNiIGI0MTEgNmNiZgo1YzM4IDExMjggZDljZiAxMDkxIDgyY2IgYTNiYiBkZWViIGEwMDYKZDY3MyA3MWEyIGI4ZTYgZGE1ZSBkMjFiIDhiNzMgOTAyZSA0NmE2CmNhZGEgODg1ZSBiOWUxIDczM2YgYjZjNyA2MmI3IDRmNTIgNmEzZQoyOTkwIDZkNzkgZDg1NSAwMDdiIGZlN2UgNzQxNiA0NTVjIDY0ODIKZThmZSA0OTZlIDg1MDggMTdlOSA0ODBkIGY3NWMgNmNlMiAxMGRhCjA0MDggMjZmOSBiNjVkIDJjZmMgZDc1YyBmZGM3IGIzZjEgNmQ2Mgo5YWY1IGI5OTYgODJkOCA3NGM5IDhmMTQgYjEwMiBhODRjIDZmZWQKY2FlMCAxOWI3IGFlYWQgYWQxNiBkNzcwIDdjMTkgNmNjNSBlZmVjCjFlY2QgMTkxMSBjM2FlIDk2NGEgZTlmYSAzMWFjIGI3YzEgNTJiOQo0MmUzIDRiMzIgMzdmOCBmODIzIDZkZGMgNDQzMCAzZTlmIDA5MzMKNzYyMyAzYTBmIGUyZWUgYzcwMyA1MmIxIDYxMTkgZmM5NiA3ZTA3CmRmMjIgNjA3ZSA5YzcyIDQyNDQgMDAyNSA3MDIxIDA4MGUgOWMzYwo1ZGNkIGFjMzQgMTVkNSA0OWVjIDZhY2YgODU2OSBkYmI4IDhmYWQKNTA4NyAyMmE2IDkyMDkgZTI1MiBiMzQ4IDBjMDIgYjgwZiBkMmFiCjI1MTQgZDUyZCA0MmQ0IGZiMjggNzM2ZiBiNzVlIDY1NGI=",
        "PrivateKey": "MzA4MiAwNTBlIDMwNDAgMDYwOSAyYTg2IDQ4ODYgZjcwZCAwMTA1CjBkMzAgMzMzMCAxYjA2IDA5MmEgODY0OCA4NmY3IDBkMDEgMDUwYwozMDBlIDA0MDggMDIwMSAwMDAyIDgyMDEgMDEwMCAwMjAyIDA4MDAKMzAxNCAwNjA4IDJhODYgNDg4NiBmNzBkIDAzMDcgMDQwOCAzMDgyCjA0YmUgMDIwMSAwMDMwIDA0ODIgMDRjOCA3ODEwIDc5N2IgNjhkNAo4MjY4IGMwYzAgZDU1OSA1MWExIDcwOTkgZTU0NSAyNmE4IDRhYzAKNmVlNyAyNGVlIGU4YzMgOWU2MiBiMTQ5IGIyYmMgN2I1ZiAzZDExCmExYTYgNTE2NCBiZGRiIDgyMzggMWJlNyBhZjA0IGRjY2IgMTcxMwplNmFmIDJjMWEgYWIwMyA3MjgwIGVhNzcgMjRiZiBkZmMwIGNkNjcKNTE0ZiBlOTFiIDRlN2MgNDM2MSBmY2VkIDFlZGUgYzFjZiA5OThhCjM4ZTYgNmViZiBmMjQ0IDY1NWQgYTg0ZCA4OTc1IDk5NjMgNTU4NAplMDc2IGQ4NzIgZTFlYSAxM2QxIGEzZGUgNDExYSBkMjk4IDA4YTEKNGE5YyA0ZTY4IDhhN2EgZmEzOCA5NDUzIDk5NDEgZTI3YiBmNzIyCmM1MDAgODkwNyA1ZTUxIDQzNGMgZWE1NyBhZGM3IDM4ZjkgMGY3NgpjYjJhIDkzZDMgN2UwMSAwMWU5IDNkMmEgMTMxYSAwYmY0IGMxZWUKNjBhOSAzNGE4IGIzZGYgZWE1NCAzZGQ1IGU2NWEgMzlhNyA3NGE2CjMyZjUgYzBiZiA1MDg5IDRiZjkgMDlmZiA2Y2U1IGU2MDEgNjUxZAo3Y2M0IGYzOWMgMzRkNSA2MGM0IGJhYTcgZDA0NiA1ODI5IDcwMGYKNWUzNSA5MmE2IGE5MTMgYTE0OCA5MjYzIGVjZmYgYzkxOSAxMzc4CmRjNjAgOWY4NCBjY2FiIDc2MjEgZWMzZiA2OGMyIGZkNmMgODNmNgpmMzk5IDlkYjggMTlkNCA3OWM0IDU3YjUgYjNiOCBiMDViIGMzZmIKNzk1OCBlM2ZiIDk3NTcgMjFjZSA3Njc3IGNmNjIgZWIwYiAxYWUyCjc4ZGQgMTFjMyA3NjVhIDVkNTkgNTZhOSBlNmFkIDczYTMgYWVjZQoyZjUyIGQyZjkgNzMzZiA2OWNhIGE4MjYgZDQ3YiA4NjYzIGUzOTkKZmUzYyAzZGQ4IDcxN2MgZDBkZiBkMDA1IDQxZTUgNWJiMCA1MGI5CjMwYzggNDllNSA3YzcxIDNmMTggNWExMiAyMWY2IGNlNTQgYTNhNgpjMzBhIDgxMTkgOTQ4MyBmMGFlIDdiZGIgNDQyZSBjNDE4IDU2NmUKNjY1NCA0ZDYzIDk2ODYgNTczYiA4YjEyIGM0NGQgMGJmNSA3Mjg0CjJmZTYgMmQzYiAwOGNmIDk2ZDYgMGFjOSBmMTc4IDViMzYgNWYxZgo1NGFkIGY0N2IgNTkyMSAzNDg4IDFmNmQgMzUyOCAyZTI1IGZhYzUKYTA4YyA4ZWFhIDJlMGMgZmIzZSAyZjkxIDMzNDkgZjBkZiBhYmFmCjM4OWEgZjBiNCBhNmVkIGZlZWIgZTdkZCBhMTBlIDBmNzYgM2U1ZQo5MGZjIDU0ODIgY2M3NSAzMjgxIGQ2MWQgZGYyNiBmYTRmIDE5YTQKNTc0YyBhMjA3IDI2MjggMTRhZSAxMmM2IDJiZTIgZDA4ZiBkYWJjCmEyMGUgMGQyZiBlNzc4IGM0NDIgMmUwYyA4MjQzIGM5NzUgM2UwYgo0MTVhIDY5M2YgNTEwYyBhZTY0IDBmZTcgOWY3OSBjNTA3IDg2ZDcKNTI4YSAwZWFhIDM5Y2UgZDE0ZiBlNTNmIDE3ODAgYmJjNCA5ODkyCjIwZTUgOGRiOSAwNWQxIGEyMGIgNjUxZSA1YjFjIDk3MDAgZjQ3MAoyNzdlIGNlNWYgNjNiYSAwYWI3IGFkYmUgYzNiYiA3MmNkIGQwZGEKYmViZSA3MzAwIDg2ODUgODFiYyBlODIxIDA4YzEgNzg3YSBjMWIxCjAxYzcgZWIwMSBhNGVmIDY3MGYgNDY3YiA0MzI1IGYxYTMgZDViMApjYzViIGVmMjIgMzIzOCAwNWEwIDUzNWQgNjZhNyBjMjk4IDU2Y2IKZTA2MCA4MmMzIDY1ZTAgNjJiMCAxYzE1IGQ4Y2IgMjA3NSBjZGNlCjQzOWUgNzQ1MSBjZGU1IDMzMGIgOTc1NyA0ZjEzIDcxNTggZWY1Nwo1YjJlIDkwZGEgYjNkMiA2MjcwIGQ0ZmMgYWRjMCAxMTBjIDMzZmQKNWViZCBmZDg2IGMyOGQgNmZmYyA3NDliIDdmYzAgYmFkMiA1MWRjCjhhODIgMDY1NyBlZjdiIGE4ZTAgZDdjZiBhY2IwIDM5MmMgNmRhZAo5NTY2IDZiM2MgN2QxMiBiNzlhIGU4ZDQgZGEzYSA2MjI2IDk2NjIKOGEzNSBiOWRkIGYzMmYgNzAyNSBjMTZjIGMwOGIgMTUwMiA4NDhjCjJkMGQgNzM2YSA3ODFiIDgyYjggNzFkNiBlZDVlIGE4NTUgM2JkNgoxYTE4IDU4MDIgYjE4ZiBmZjllIDkyOWIgYjJmOCBlOTg0IDg3OGQKYzk2ZiBmMWFlIDBhYWIgNjdkNCA4Njg3IDRmOWYgZTJlMyA1ZDljCjlhOWEgZmEyMiA1YzNhIDM3OTEgNmUxNCA5MGMyIGJkZjkgODY4OAozYmE1IDYyOGMgY2YzZSA4ZDVkIDczZDggZmE5YSA5YmY4IGE3Y2IKMjZkZSA5YjRiIDlhZDQgYTdhYyA5ZTJlIDljOTQgMTY2MCBjNTdkCmVhMzUgYjMyZCBiYWE1IDJjMTkgODQ0MiAyNTkwIGNkMTkgYmUxYgo3Y2MxIGY1ZTkgNzg5OCBlMDIwIDYyNzAgM2NkMSBkMzA3IDI4NGUKZDEyMSA1NWE3IGM1ZTIgYzgyZiAwZjhkIDkwZGUgYzdlZSBjODU3CmJkYjkgN2U1OSA0ZGIxIDEyMWUgOGVlOSA3NzFhIGNiMzQgZjIyZAoxNmMxIDdkZTEgNTg5ZCAzNjZjIGY0YmUgYWJlYiAxNGJiIGRlMTcKYjljZCA4M2JjIDU0OGIgMzFiYSAwODU4IDE2NGIgY2ZhMiA2ZDQ4CmUzODQgMjM5NSA0MjU3IGRhY2YgYmQwZCBhMTRiIDU1OTQgNDI4OAowOTJmIGUwNGYgZTM1OCAxMTJmIGExZTcgNjU1YyAyOTBiIDc5ZmMKOTI1NyBkN2Q4IDI2OGMgM2JlNyA4NTY2IGYxMjQgMDlmNSBlMDNkCjZiMmIgODM4NiAzYWZlIDVkZjkgZTUxYiAzZTEzIDkwNDEgNGFiNwplOGZhIGVkMGMgNzMxOCAwZjAzIDU4YTUgYjdiYSBkODcyIGNlMzgKNzExNiA3ZjYwIDE1MGIgMTgzMiAxNzViIDRmNzUgODQ3NCA4Y2YxCmIzNTQgZmJhZSBjZDViIGNiMzEgODBjYiA2MDI5IDdmMmEgYTRiNAo5NjEwIGE0NGQgZTI1YyBkZDFmIDgwZmUgNTQyNSAzYTcyIGJhMzcKZDNmNiA2NGUwIGRmMWMgNGZhMiA2NGRjIDljNGMgMmIwYiBjZThlCjgwMTQgNzUyZSBjM2NhIGM4ZTIgNDc2MSA1ZGVlIGQ5MTYgNDY2YwowYjhlIGVlNmUgMzZjNyA0NzgyIDliZWMgZTIxYyA4YzdlIGQxNTgKM2YwZCAyOWY4IDc2NDQgYTA5NSBlYWE2IDg1OTggZDBlNCBjMjJmCjdiODMgMWQ4ZiA2NTZkIGQ4YmMgZjIyYiA5NjBmIGU3MWMgZTkzMAo4NmJmIGRlMjQgODViZCBiYmExIDA4YTUgYWQ2NSAxYzQxIDA1OTEKMDczMSBhMTkwIGE0ZWIgYzU4YSAyZDhiIDE5OWMgNWE5ZSBmNWJhCjhjMTkgOWRjOSA2ZmNkIDRiZGMgYjgzMyA2ODNlIGMwYTIgZWZhOQpjNWIwIDFiYWMgN2I4NSBiZGE2IGExMjkgMDMzYSAzOThkIGE3NDkKMDc0NSAxMzQwIDQ4NjkgNjE5MiA0NWU2IGZmNjYgZWI5NSAxMWU0CmU1NjIgMTMxNiA5NzgzIGUzY2UgN2Q0OCAwNGIzIGMzYTkgMzkzNQo3NDdmIDJiNzQgNjNlOCA5NDMzIDZmODEgYzU4MCBjNGFmIDQ3ZjEKOGQ2Mg==",
        "PrivateKeyPassword": "12345678a"
    };

    var rfc = "URE180429TM6";
}
else
{
    //CSD de pruebas
    var newCsd =
    {
        "Rfc": "ETI120511BF1",
        "Certificate": "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",
        "PrivateKey": "MIIFDjBABgkqhkiG9w0BBQ0wMzAbBgkqhkiG9w0BBQwwDgQIAgEAAoIBAQACAggAMBQGCCqGSIb3DQMHBAgwggS+AgEAMASCBMgdSCYRgQu88dt4LNobmZrQgDRz1LJpxqCo+Kh8csSKUSPf2lVOC1feRAvA02diINP1vEBAhGSINrw85C999kAch3+D/AOVx2qZAIF2Ij4mxx7Qz/6ABdDvaVd4KxX9c513mQso0M8AZv5rNZCF9/ydQMDF4oGpPnGwAD9wDKRBagXyL55EKw7xagwA6/zUuxiKx7VoaLzteexngae4HMQ0Q6PGjHd5GYYh6bKA3gAiH2v/wFYDhVJ4XL010678ihKY1TQhUrwNQONdVJAwByB6yivlpRXyBD44iw1vhI585l3mjqWXHcZx8sUOjM4XOgT2jsQkeVVJA2KIwnpiiT7Rafd9nW0vHu3AruG33hd9LnLu5SB4SkJgAbP7hZVlexMVAIuI7zwE9yWgUxS2zKMP+Eb6IYrf0gyV0+ov3KekXycS2xM9b/vaKd0dH3OJCtY3r2U6laZs0SP8oYDA0pJwNoncp8HJsdiN8XrP9e9RVA6z+lu9NONOyWuUWN/+Gl2mqww0hosSyf0SW+rHjDMzd3ZUyKxUCScFk7D8F3yCDMF/ffxyrb6SkaKT06VOrTXajyyxunr2b+XkJO2WbnGKch9jYwJP8l8wgRfPwJHiKiEzoaGP4HnSdLZHb0PG08H6n1tivYLLffMTyiD8kxgSug/Zh2ipiPRzjUbjYSbFLE31OR31lbjMC0g4opzs9Tffdqp5xEq5ZRWcAO/+edpGTch/KOATXBqR62JejQHFzZW/mD9FZTYM6iyNYdCKGmY7oIkzt70tsRB8qZtUBFMnUYGuXWed853SUBZl+xXozxMOzBmnPx+DLEeKAvtId2JeKtRbQC0SqVCBxhZDufvLL+RFCNq+11+rRnm7ccHyBP8EVXSq6R59RQk5yYKoNxwck/j14GeAzPAyfXIktS+N+FG7J4CShIyfEQKuylsksoHHt7gfXv3OAMv3yiYvWm1J0ErcuWS7ZGz57pSpkqXGhiARn2xQ4Oud51AZM8heH7IP1fc6Ia6S6Ubw4M91EHBHyZrcNeY7LeRj6psZyUsIK5HN1KIwmeTqRAfKS2vjl9h0FMkey7lHZwjRAsIuL2JJyYDUNAoZI96a8AaH5emwlsWofaDBdyzzT1M6fbRJrJ3K/E9vnkzKJlf5oHPwAJc3A6f/hS0GmpYP6XBdLKZcN015aZqHtLJkQPLhVCHGy/6cshvV+qmPyRb1PChQohxV0JWRljiX+HH6O9O2+B/JKR+fzeyDb11uBhwlX658yLxZ9o5GpunomZcgubZYR8fwyQBQtboImjB8tJuaYT2frc1DoMdAZwZBzit6Bmoh3e1m4lzDbZsBLzSl3SnBwIeU3zsTgssJqEup7hLIwk0nLCg19sSPdODd2H9eThTJTQX3cloy8wj8HEH4/TZr4BfRUARz7jSeKOmBXkeQ0GiYDCrDhtilUrSOc41aSBbErrbbQwtVc8Pny2P6ZBoydEB3nJVlnpZa8o2WOYNhqYtP8dsNRWAbdJdNFd6JamBiJqUPzGCAjrKnSIeKmJWHZNj6kCXKXYOo6BIh8ph++K/rk1vF7RvQXuj7jC1qpXTBVplM7sjL7GuxQxOb4jwT73QbrvZgMXGtpLg2O527/ZsH+lA3p+VbzaM=",
        "PrivateKeyPassword": "3LevenT1"
    };
}*/

var Rfc = $("#rfcCsd").val();
var Certificate = $("#base64cer").val();
var PrivateKey = $("#base64key").val();
var PrivateKeyPassword = $("#passSellos").val();

var newCsd =
{
    "Rfc": Rfc,
    "Certificate": Certificate,
    "PrivateKey": PrivateKey,
    "PrivateKeyPassword": PrivateKeyPassword
};

function testCertificates()
{
    var certif;
    
    //obtener todos los certificados dados de alta para el usuario
    /*Facturama.Certificates.List(function (result)
    {
        console.log("todos los CSD ", result);
    });*/

    //obtener los certificados de un rfc en especifico
    /*Facturama.Certificates.Get(rfc, function (result)
    {
        certif = result;
        console.log("obtener CSD de un RFC ", result);
    });*/

    //eliminar los certificados del rfc especificado
    /*Facturama.Certificates.Remove(rfc, function (result)
    {
        console.log("se elimino", result);
    }, function (error)
    {
        if (error && error.responseJSON)
            console.log("errores", error.responseJSON);
    });*/

    //se agregan unos nuevos csd 
    /*Facturama.Certificates.Create(newCsd, function (result)
    {
        certif = result;
        console.log("se agrega nuevo RFC y CSD,", result);
    }, function (error)
    {
        if (error && error.responseJSON)
            console.log("errores", error.responseJSON);
    });*/

    //se actualiza el certificado de un rfc en especifico
    /*newCsd.PrivateKeyPassword = "3LevenT1";
    Facturama.Certificates.Update(rfc, newCsd, function (result)
    {
        certif = result;
        console.log("actualizacion csd", result);
    }, function (error)
    {
        if (error && error.responseJSON)
            console.log("errores", error.responseJSON);
    });*/
}