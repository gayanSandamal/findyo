export default {
  data () {
    return {
      completion: [
        {
          fields: [
            {
              required: true,
              type: 'single-text',
              label: 'Username',
              name: 'username'
            },
            {
              label: 'First Name',
              required: true,
              type: 'single-text',
              name: 'firstname'
            },
            {
              type: 'single-text',
              label: 'Last Name',
              name: 'lastname'
            },
            {
              type: 'single-date',
              label: 'Date of Birth',
              name: 'dateofbirth'
            },
            // {
            //   label: 'Job Title',
            //   type: 'single-text',
            //   name: 'job'
            // },
            {
              value: [
                {
                  type: 'tel',
                  value: '',
                  name: 'tel'
                }
              ],
              type: 'numeric-array',
              label: 'Contact Numbers'
            },
            {
              type: 'single-email',
              label: 'email',
              name: 'email'
            }
          ],
          page_name: 'Basic Information'
        },
        {
          page_name: 'Location details',
          fields: [
            {
              type: 'single-text',
              label: 'Address',
              name: 'address'
            }
            // {
            //   type: 'input-array',
            //   label: 'Area',
            //   value: [
            //     {
            //       type: 'single-text',
            //       label: 'City',
            //       name: 'city'
            //     },
            //     {
            //       label: 'District',
            //       type: 'single-text',
            //       name: 'district'
            //     },
            //     {
            //       type: 'single-text',
            //       label: 'Province',
            //       name: 'state'
            //     },
            //     {
            //       type: 'single-text',
            //       label: 'Country',
            //       name: 'country'
            //     },
            //     {
            //       type: 'single-numeric',
            //       label: 'Postal code',
            //       name: 'postalcode'
            //     }
            //   ]
            // }
          ]
        },
        {
          fields: [
            {
              label: 'About',
              type: 'single-textarea',
              name: 'bio'
            }
            // {
            //   label: 'Skills',
            //   type: 'text-array',
            //   name: 'skills'
            // },
            // {
            //   label: 'Preferences and Interests',
            //   type: 'text-array',
            //   name: 'interests'
            // }
          ],
          page_name: 'Profile description'
        }
      ]
    }
  }
}
