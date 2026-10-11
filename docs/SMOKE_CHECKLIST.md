# Recrouter smoke checklist

Use this after migrating a database and starting `bin/cake server`.

- [ ] `/users/login` renders and accepts `admin@example.com` / `password` on a fresh seed
- [ ] Register (`/users/add` or `/Users/register`) and password reset (`/users/forgot`, `/users/reset`)
- [ ] Dashboard `/` loads job and candidate counts
- [ ] Jobs index/search, add, view, edit, archive
- [ ] Candidates index/search, add, view, edit, archive, file upload
- [ ] Link a candidate to a job; hire / reject / reopen from `/CandidatesJobs/hire/{id}` etc.
- [ ] Workflow builder: add workflow, steps, reorder, assign users
- [ ] Survey builder on a job and public `/surveys/response/{id}/{linkId}`
- [ ] Departments tree add/edit/delete
- [ ] Groups and users settings (`/Settings`)
- [ ] Tag autocomplete JSON (`/tags/search/{text}`)
- [ ] AJAX favorites and linked-candidate panel on job view
